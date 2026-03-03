<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    /**
     * Exibe lista de produtos da loja autenticada.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        $products = $user->products()
            ->with('category')
            ->latest()
            ->paginate(15);
        
        // Estatísticas
        $stats = [
            'total_products' => $user->products()->count(),
            'active_products' => $user->products()->where('is_active', true)->count(),
            'inactive_products' => $user->products()->where('is_active', false)->count(),
            'low_stock' => $user->products()->where('stock', '<=', 10)->count(),
        ];
        
        return view('loja.products.index', compact('products', 'stats'));
    }

    /**
     * Exibe formulário de criação de produto.
     */
    public function create(): View
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        
        return view('loja.products.create', compact('categories'));
    }

    /**
     * Armazena um novo produto.
     * 
     * Nota importante sobre SLUG:
     * - O slug NÃO é enviado no formulário
     * - O slug é gerado AUTOMATICAMENTE pelo Model (Product::booted)
     * - Não precisa validar nem processar slug aqui
     * - O Model garante unicidade do slug por loja (user_id)
     */
    public function store(): RedirectResponse
    {
        $this->authorize('create', Product::class);
        
        $user = auth()->user();
        
        // Validação: NÃO inclui 'slug' - ele é gerado automaticamente
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'promotional_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured' => 'boolean',
        ]);
        
        // Validar promotional_price
        if ($validated['promotional_price'] ?? null) {
            if ($validated['promotional_price'] >= $validated['price']) {
                return back()->withInput()->with('error', 'Preço promocional deve ser menor que o preço regular.');
            }
        }
        
        // Processar imagem
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $path = $image->store('products', 'public');
            $validated['image'] = $path;
        }
        
        // Associar produto à loja autenticada
        $validated['user_id'] = $user->id;
        $validated['is_active'] = true;
        
        // Criar produto - o slug será gerado automaticamente pelo Model
        Product::create($validated);
        
        return redirect()->route('loja.products.index')
            ->with('success', 'Produto criado com sucesso.');
    }

    /**
     * Exibe formulário de edição de produto.
     */
    public function edit(Product $product): View
    {
        $this->authorize('update', $product);
        
        $categories = \App\Models\Category::where('is_active', true)->get();
        
        return view('loja.products.edit', compact('product', 'categories'));
    }

    /**
     * Atualiza um produto.
     * 
     * Nota importante sobre SLUG:
     * - O slug NÃO é enviado no formulário
     * - Se o nome mudar, o slug é regerado AUTOMATICAMENTE pelo Model
     * - Não precisa validar nem processar slug aqui
     */
    public function update(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        // Validação: NÃO inclui 'slug' - ele é atualizado automaticamente se o nome mudar
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'promotional_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured' => 'boolean',
        ]);
        
        // Validar promotional_price
        if ($validated['promotional_price'] ?? null) {
            if ($validated['promotional_price'] >= $validated['price']) {
                return back()->with('error', 'Preço promocional deve ser menor que o preço regular.');
            }
        }
        
        // Processar imagem
        if (request()->hasFile('image')) {
            // Deletar imagem antiga se existir
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            
            $image = request()->file('image');
            $path = $image->store('products', 'public');
            $validated['image'] = $path;
        }
        
        $product->update($validated);
        
        return redirect()->route('loja.products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Ativa/Desativa um produto.
     */
    public function toggleActive(Product $product): RedirectResponse
    {
        $this->authorize('toggle', $product);
        
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "Produto $status com sucesso.");
    }

    /**
     * Deleta um produto.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);
        
        // Deletar imagem se existir
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('loja.products.index')
            ->with('success', 'Produto deletado com sucesso.');
    }

    /**
     * Cria uma categoria rapidamente a partir do formulário da loja.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'slug' => $slug,
            'is_active' => true,
            'order' => 0,
        ]);

        return redirect()->route('loja.products.create')
            ->with('success', 'Categoria criada com sucesso.');
    }
}
