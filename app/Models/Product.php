<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'promotional_price',
        'stock',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotional_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot do model - configura eventos automáticos.
     */
    protected static function booted(): void
    {
        // Evento CREATING: executa ANTES de salvar no banco
        // Garante que o slug seja gerado automaticamente
        static::creating(function (Product $product) {
            // Se o slug não foi informado, gera automaticamente
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name, $product->user_id);
            }
        });

        // Evento UPDATING: executa ANTES de atualizar no banco
        // Garante que se o nome mudar, o slug seja atualizado
        static::updating(function (Product $product) {
            // Se o nome mudou, regenera o slug
            if ($product->isDirty('name')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->user_id, $product->id);
            }
        });
    }

    /**
     * Gera um slug único para a loja específica.
     * 
     * Lógica:
     * 1. Cria slug base a partir do nome (ex: "Hambúrguer" -> "hamburguer")
     * 2. Verifica se já existe para essa loja (user_id)
     * 3. Se existir, adiciona sufixo incremental: hamburguer-2, hamburguer-3...
     * 4. Garante unicidade por loja (não global)
     * 
     * @param string $name Nome do produto
     * @param int $userId ID da loja (user_id)
     * @param int|null $excludeId ID do produto a excluir da busca (usado em updates)
     * @return string Slug único para essa loja
     */
    protected static function generateUniqueSlug(string $name, int $userId, ?int $excludeId = null): string
    {
        // Gera slug base a partir do nome
        $baseSlug = Str::slug($name);
        
        // Se o slug base estiver vazio (nome só tem caracteres especiais), usa 'produto'
        if (empty($baseSlug)) {
            $baseSlug = 'produto';
        }
        
        $slug = $baseSlug;
        $counter = 2; // Começa em 2 (o primeiro não tem sufixo)
        
        // Loop até encontrar um slug único para essa loja
        while (static::slugExistsForUser($slug, $userId, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Verifica se um slug já existe para um user_id específico.
     * 
     * @param string $slug Slug a verificar
     * @param int $userId ID da loja
     * @param int|null $excludeId ID do produto a excluir da busca (usado em updates)
     * @return bool True se o slug já existe para essa loja
     */
    protected static function slugExistsForUser(string $slug, int $userId, ?int $excludeId = null): bool
    {
        $query = static::where('slug', $slug)
            ->where('user_id', $userId);
        
        // Se estamos atualizando, exclui o próprio produto da verificação
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Get the shop/user that owns this product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shop that owns this product (alias).
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all order items for this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the current price (promotional if available).
     */
    public function getCurrentPrice(): float
    {
        return $this->promotional_price ?? $this->price;
    }

    /**
     * Check if product is on sale.
     */
    public function isOnSale(): bool
    {
        return $this->promotional_price !== null && $this->promotional_price < $this->price;
    }

    /**
     * Check if product is in stock.
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }
}
