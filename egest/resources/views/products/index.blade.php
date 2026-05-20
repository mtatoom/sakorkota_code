<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Liste des Produits - {{ request()->getHost() }}</h1>
            <span class="badge bg-primary fs-6">Mode Multi-Tenant</span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>SKU</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th class="text-end">Prix d'achat</th>
                            <th class="text-end">Prix de vente</th>
                            <th class="text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                                <td class="text-end">{{ number_format($product->purchase_price, 0, ',', ' ') }} Ar</td>
                                <td class="text-end text-success fw-bold">{{ number_format($product->sale_price, 0, ',', ' ') }} Ar</td>
                                <td class="text-center">
                                    <span class="badge {{ $product->stock_quantity > 5 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucun produit disponible pour cette boutique.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
