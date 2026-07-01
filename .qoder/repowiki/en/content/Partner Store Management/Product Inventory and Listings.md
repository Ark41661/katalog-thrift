# Product Inventory and Listings

<cite>
**Referenced Files in This Document**
- [PartnerProductController.php](file://app/Http/Controllers/Partner/PartnerProductController.php)
- [PartnerBulkController.php](file://app/Http/Controllers/Partner/PartnerBulkController.php)
- [Product.php](file://app/Models/Product.php)
- [ProductVariant.php](file://app/Models/ProductVariant.php)
- [catalog.php](file://config/catalog.php)
- [create.blade.php](file://resources/views/partner/products/create.blade.php)
- [edit.blade.php](file://resources/views/partner/products/edit.blade.php)
- [web.php](file://routes/web.php)
- [2026_05_04_125734_create_products_table.php](file://database/migrations/2026_05_04_125734_create_products_table.php)
- [2026_07_01_100002_create_product_variants_table.php](file://database/migrations/2026_07_01_100002_create_product_variants_table.php)
- [2026_07_01_100007_add_seo_and_search_to_products.php](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php)
- [SearchController.php](file://app/Http/Controllers/SearchController.php)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Analysis](#dependency-analysis)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)
10. [Appendices](#appendices)

## Introduction
This document explains how partner users manage product inventory and listings in the platform. It covers product creation, categorization, pricing, variants, media, SEO, search, inventory tracking, bulk operations, and status management. It also outlines how product modifications, deletions, and archiving work, along with best practices for photography, descriptions, and inventory organization.

## Project Structure
The product lifecycle spans controllers, models, views, routes, configuration, and database migrations. Key areas:
- Routes define partner product CRUD, bulk actions, and variant management endpoints.
- Controllers handle validation, persistence, media handling, slug generation, and variant synchronization.
- Models encapsulate relationships, attributes, and search scopes.
- Views render forms for creating and editing products, including size charts and variants.
- Configuration defines product categories, size chart templates, and defaults.
- Migrations define schema for products and variants, plus search and SEO fields.

```mermaid
graph TB
subgraph "Routes"
R1["web.php<br/>Partner product routes"]
end
subgraph "Controllers"
C1["PartnerProductController.php"]
C2["PartnerBulkController.php"]
C3["SearchController.php"]
end
subgraph "Models"
M1["Product.php"]
M2["ProductVariant.php"]
end
subgraph "Views"
V1["create.blade.php"]
V2["edit.blade.php"]
end
subgraph "Config"
K1["catalog.php"]
end
subgraph "Database"
D1["2026_05_04_125734_create_products_table.php"]
D2["2026_07_01_100002_create_product_variants_table.php"]
D3["2026_07_01_100007_add_seo_and_search_to_products.php"]
end
R1 --> C1
R1 --> C2
R1 --> C3
C1 --> M1
C1 --> M2
C2 --> M1
C3 --> M1
V1 --> C1
V2 --> C1
K1 --> V1
K1 --> V2
M1 --> D1
M2 --> D2
M1 --> D3
```

**Diagram sources**
- [web.php:119-167](file://routes/web.php#L119-L167)
- [PartnerProductController.php:14-337](file://app/Http/Controllers/Partner/PartnerProductController.php#L14-L337)
- [PartnerBulkController.php:10-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L10-L75)
- [SearchController.php:8-56](file://app/Http/Controllers/SearchController.php#L8-L56)
- [Product.php:9-132](file://app/Models/Product.php#L9-L132)
- [ProductVariant.php:6-23](file://app/Models/ProductVariant.php#L6-L23)
- [create.blade.php:1-300](file://resources/views/partner/products/create.blade.php#L1-L300)
- [edit.blade.php:1-274](file://resources/views/partner/products/edit.blade.php#L1-L274)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)
- [2026_05_04_125734_create_products_table.php:12-26](file://database/migrations/2026_05_04_125734_create_products_table.php#L12-L26)
- [2026_07_01_100002_create_product_variants_table.php:8-31](file://database/migrations/2026_07_01_100002_create_product_variants_table.php#L8-L31)
- [2026_07_01_100007_add_seo_and_search_to_products.php:8-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L8-L28)

**Section sources**
- [web.php:119-167](file://routes/web.php#L119-L167)
- [PartnerProductController.php:14-337](file://app/Http/Controllers/Partner/PartnerProductController.php#L14-L337)
- [Product.php:9-132](file://app/Models/Product.php#L9-L132)
- [ProductVariant.php:6-23](file://app/Models/ProductVariant.php#L6-L23)
- [create.blade.php:1-300](file://resources/views/partner/products/create.blade.php#L1-L300)
- [edit.blade.php:1-274](file://resources/views/partner/products/edit.blade.php#L1-L274)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)
- [2026_05_04_125734_create_products_table.php:12-26](file://database/migrations/2026_05_04_125734_create_products_table.php#L12-L26)
- [2026_07_01_100002_create_product_variants_table.php:8-31](file://database/migrations/2026_07_01_100002_create_product_variants_table.php#L8-L31)
- [2026_07_01_100007_add_seo_and_search_to_products.php:8-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L8-L28)

## Core Components
- PartnerProductController: Handles product creation, updates, deletion, media replacement, slug generation, size chart parsing, and variant management.
- PartnerBulkController: Provides bulk activation/deactivation, marking sold/new arrival, and CSV export for partner’s products.
- Product model: Defines fillable attributes, casts, relationships (partner, variants, reviews, reports), computed attributes (image URL, average rating, review count), and search scope.
- ProductVariant model: Defines variant attributes and belongs-to relationship to Product.
- Views: Forms for creating and editing products, including size chart and variants tables.
- Routes: Expose endpoints for CRUD, variants, and bulk operations under the partner namespace.
- Config: Provides product types, size chart columns and defaults used in forms.
- Migrations: Define products, variants, and search/SEO fields.

**Section sources**
- [PartnerProductController.php:14-337](file://app/Http/Controllers/Partner/PartnerProductController.php#L14-L337)
- [PartnerBulkController.php:10-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L10-L75)
- [Product.php:9-132](file://app/Models/Product.php#L9-L132)
- [ProductVariant.php:6-23](file://app/Models/ProductVariant.php#L6-L23)
- [create.blade.php:1-300](file://resources/views/partner/products/create.blade.php#L1-L300)
- [edit.blade.php:1-274](file://resources/views/partner/products/edit.blade.php#L1-L274)
- [web.php:119-167](file://routes/web.php#L119-L167)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)
- [2026_05_04_125734_create_products_table.php:12-26](file://database/migrations/2026_05_04_125734_create_products_table.php#L12-L26)
- [2026_07_01_100002_create_product_variants_table.php:8-31](file://database/migrations/2026_07_01_100002_create_product_variants_table.php#L8-L31)
- [2026_07_01_100007_add_seo_and_search_to_products.php:8-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L8-L28)

## Architecture Overview
The partner product module follows MVC with explicit separation of concerns:
- Routes delegate to PartnerProductController for product operations and PartnerBulkController for batch actions.
- Controllers coordinate validation, persistence, media handling, and variant synchronization.
- Models encapsulate domain logic and relationships.
- Views render forms and present configuration-driven UI (categories, size chart columns).
- Database migrations define schema and indices for search and SEO.

```mermaid
sequenceDiagram
participant Browser as "Partner Browser"
participant Routes as "web.php"
participant Ctrl as "PartnerProductController"
participant Model as "Product"
participant Variant as "ProductVariant"
participant Storage as "Storage"
Browser->>Routes : GET /mitra/produk/tambah
Routes-->>Browser : create.blade.php
Browser->>Routes : POST /mitra/produk (with image or URL)
Routes->>Ctrl : store(request)
Ctrl->>Ctrl : validate input
Ctrl->>Ctrl : parseSizeChart(request)
Ctrl->>Storage : upload image (optional)
Ctrl->>Model : create(product)
alt has_variants
Ctrl->>Variant : delete existing variants
loop variants[]
Ctrl->>Variant : create(variant)
end
end
Ctrl-->>Browser : redirect to index with success
```

**Diagram sources**
- [web.php:127-133](file://routes/web.php#L127-L133)
- [PartnerProductController.php:42-133](file://app/Http/Controllers/Partner/PartnerProductController.php#L42-L133)
- [Product.php:13-34](file://app/Models/Product.php#L13-L34)
- [ProductVariant.php:8-16](file://app/Models/ProductVariant.php#L8-L16)

**Section sources**
- [web.php:119-167](file://routes/web.php#L119-L167)
- [PartnerProductController.php:42-133](file://app/Http/Controllers/Partner/PartnerProductController.php#L42-L133)
- [Product.php:13-34](file://app/Models/Product.php#L13-L34)
- [ProductVariant.php:8-16](file://app/Models/ProductVariant.php#L8-L16)

## Detailed Component Analysis

### Product Creation Workflow
- Form fields include name, brand, product_type, style_type, price, size, condition, description, story, image selection (upload or URL), external store links, size chart toggle, variants, SEO metadata, and status flags.
- Validation ensures required fields and safe sizes for images and URLs.
- Media handling supports either uploading a file (stored in public disk) or supplying a URL; previous file is deleted upon replacement.
- Slug generation ensures uniqueness per partner.
- Size chart is parsed from submitted rows and stored as an array.
- If variants are enabled, existing variants are cleared and re-created from the submission.

```mermaid
flowchart TD
Start(["Submit Create Form"]) --> Validate["Validate Request Fields"]
Validate --> ImageChoice{"Image Source?"}
ImageChoice --> |Upload| Upload["Store File to Public Disk"]
ImageChoice --> |URL| UseURL["Use Provided URL"]
Upload --> Prepare["Prepare Product Data"]
UseURL --> Prepare
Prepare --> Create["Create Product Record"]
Create --> VariantsEnabled{"Has Variants?"}
VariantsEnabled --> |Yes| ClearVars["Delete Existing Variants"]
ClearVars --> LoopVars["Loop Submitted Variants"]
LoopVars --> SaveVar["Create Variant Records"]
VariantsEnabled --> |No| Done["Done"]
SaveVar --> Done
```

**Diagram sources**
- [PartnerProductController.php:42-133](file://app/Http/Controllers/Partner/PartnerProductController.php#L42-L133)
- [create.blade.php:80-239](file://resources/views/partner/products/create.blade.php#L80-L239)

**Section sources**
- [PartnerProductController.php:42-133](file://app/Http/Controllers/Partner/PartnerProductController.php#L42-L133)
- [create.blade.php:80-239](file://resources/views/partner/products/create.blade.php#L80-L239)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)

### Product Modification and Deletion
- Edit form preloads current values and allows changing image via upload or URL.
- Slug is regenerated only when the name changes.
- Size chart and variants are fully replaced when variants are enabled.
- Deletion removes associated media file from storage before deleting the product.

```mermaid
sequenceDiagram
participant Browser as "Partner Browser"
participant Routes as "web.php"
participant Ctrl as "PartnerProductController"
participant Model as "Product"
participant Storage as "Storage"
Browser->>Routes : GET /mitra/produk/{product}/edit
Routes-->>Browser : edit.blade.php
Browser->>Routes : PUT /mitra/produk/{product}
Routes->>Ctrl : update(request, product)
Ctrl->>Ctrl : validate
Ctrl->>Ctrl : parseSizeChart
alt image_file uploaded
Ctrl->>Storage : delete old file
Ctrl->>Storage : store new file
end
Ctrl->>Model : update(product)
alt has_variants
Ctrl->>Model : variants().delete()
loop variants[]
Ctrl->>Model : variants.create(...)
end
end
Ctrl-->>Browser : redirect with success
Browser->>Routes : DELETE /mitra/produk/{product}
Routes->>Ctrl : destroy(product)
Ctrl->>Storage : delete(image_path)
Ctrl->>Model : delete()
Ctrl-->>Browser : redirect with success
```

**Diagram sources**
- [web.php:131-133](file://routes/web.php#L131-L133)
- [PartnerProductController.php:149-259](file://app/Http/Controllers/Partner/PartnerProductController.php#L149-L259)
- [edit.blade.php:75-237](file://resources/views/partner/products/edit.blade.php#L75-L237)

**Section sources**
- [PartnerProductController.php:149-259](file://app/Http/Controllers/Partner/PartnerProductController.php#L149-L259)
- [edit.blade.php:75-237](file://resources/views/partner/products/edit.blade.php#L75-L237)

### Variant Management
- Variants are optional and controlled by a flag. When enabled, existing variants are removed and replaced with submitted entries.
- Each variant includes size, optional price override, optional condition, and stock.
- Dedicated endpoints support saving variants and removing individual variants.

```mermaid
sequenceDiagram
participant Browser as "Partner Browser"
participant Routes as "web.php"
participant Ctrl as "PartnerProductController"
participant Variant as "ProductVariant"
Browser->>Routes : POST /mitra/produk/{product}/variants
Routes->>Ctrl : saveVariants(request, product)
Ctrl->>Ctrl : validate variants[]
Ctrl->>Variant : variants().delete()
loop variants[]
Ctrl->>Variant : create({size, price?, condition?, stock?})
end
Ctrl-->>Browser : back with success
Browser->>Routes : DELETE /mitra/produk/{product}/variants/{variant}
Routes->>Ctrl : destroyVariant(product, variant)
Ctrl->>Variant : delete()
alt product has no variants left
Ctrl->>Ctrl : update(has_variants=false)
end
Ctrl-->>Browser : back with success
```

**Diagram sources**
- [web.php:140-142](file://routes/web.php#L140-L142)
- [PartnerProductController.php:293-335](file://app/Http/Controllers/Partner/PartnerProductController.php#L293-L335)
- [ProductVariant.php:18-21](file://app/Models/ProductVariant.php#L18-L21)

**Section sources**
- [PartnerProductController.php:293-335](file://app/Http/Controllers/Partner/PartnerProductController.php#L293-L335)
- [ProductVariant.php:18-21](file://app/Models/ProductVariant.php#L18-L21)

### Bulk Operations
- Bulk update toggles activity, sold, and new arrival statuses for selected products owned by the partner.
- Bulk delete removes selected products.
- Export generates a CSV summary of products for the partner.

```mermaid
flowchart TD
Start(["Bulk Action"]) --> Validate["Validate product_ids and action"]
Validate --> Fetch["Fetch partner-owned products"]
Fetch --> Switch{"Action Type"}
Switch --> |activate| SetActive["Set is_active=true"]
Switch --> |deactivate| SetInactive["Set is_active=false"]
Switch --> |mark_sold| SetSold["Set is_sold=true"]
Switch --> |mark_new_arrival| SetNew["Set is_new_arrival=true"]
SetActive --> Count["Count affected"]
SetInactive --> Count
SetSold --> Count
SetNew --> Count
Count --> Done["Redirect with success"]
```

**Diagram sources**
- [web.php:135-138](file://routes/web.php#L135-L138)
- [PartnerBulkController.php:17-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L17-L75)

**Section sources**
- [web.php:135-138](file://routes/web.php#L135-L138)
- [PartnerBulkController.php:17-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L17-L75)

### Search and SEO
- SearchController queries active, approved products using a fulltext index when available or falls back to LIKE conditions.
- Product model exposes a search scope and computed meta fields for SEO.
- Additional SEO fields (meta_title, meta_description, meta_keywords) and counters (total_views, total_wa_clicks) are persisted.

```mermaid
sequenceDiagram
participant Browser as "Browser"
participant Routes as "web.php"
participant Search as "SearchController"
participant Product as "Product"
Browser->>Routes : GET /cari?q=...
Routes->>Search : index(request)
Search->>Product : where(is_active=true).whereHas(partner.status='approved').search(q)
Product-->>Search : collection
Search-->>Browser : search.blade.php
Browser->>Routes : GET /cari/ajax?q=...
Routes->>Search : ajax(request)
Search->>Product : where(...).search(...).take(8)
Product-->>Search : JSON [{slug,name,brand,price,image}]
Search-->>Browser : JSON
```

**Diagram sources**
- [web.php:52-54](file://routes/web.php#L52-L54)
- [SearchController.php:10-54](file://app/Http/Controllers/SearchController.php#L10-L54)
- [Product.php:122-130](file://app/Models/Product.php#L122-L130)
- [2026_07_01_100007_add_seo_and_search_to_products.php:8-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L8-L28)

**Section sources**
- [SearchController.php:10-54](file://app/Http/Controllers/SearchController.php#L10-L54)
- [Product.php:122-130](file://app/Models/Product.php#L122-L130)
- [2026_07_01_100007_add_seo_and_search_to_products.php:8-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L8-L28)

### Data Model and Schema
```mermaid
erDiagram
PRODUCTS {
bigint id PK
bigint partner_id FK
string slug UK
string name
string brand
string product_type
integer price
string size
string size_display
string color
string color_hex
string style_type
string condition
text description
text story
string image
string image_path
string shopee_url
string tokopedia_url
boolean is_active
boolean is_sold
boolean is_new_arrival
boolean has_variants
integer stock
string meta_title
text meta_description
string meta_keywords
integer total_views
integer total_wa_clicks
timestamp created_at
timestamp updated_at
}
PRODUCT_VARIANTS {
bigint id PK
bigint product_id FK
string size
integer price
string condition
integer stock
boolean is_sold
boolean is_active
timestamp created_at
timestamp updated_at
}
PRODUCTS ||--o{ PRODUCT_VARIANTS : "has_many"
```

**Diagram sources**
- [2026_05_04_125734_create_products_table.php:14-26](file://database/migrations/2026_05_04_125734_create_products_table.php#L14-L26)
- [2026_07_01_100002_create_product_variants_table.php:10-22](file://database/migrations/2026_07_01_100002_create_product_variants_table.php#L10-L22)
- [Product.php:13-34](file://app/Models/Product.php#L13-L34)
- [ProductVariant.php:8-16](file://app/Models/ProductVariant.php#L8-L16)

**Section sources**
- [2026_05_04_125734_create_products_table.php:14-26](file://database/migrations/2026_05_04_125734_create_products_table.php#L14-L26)
- [2026_07_01_100002_create_product_variants_table.php:10-22](file://database/migrations/2026_07_01_100002_create_product_variants_table.php#L10-L22)
- [Product.php:13-34](file://app/Models/Product.php#L13-L34)
- [ProductVariant.php:8-16](file://app/Models/ProductVariant.php#L8-L16)

## Dependency Analysis
- Controllers depend on models and configuration for rendering forms and validating inputs.
- Product model depends on storage for image URL resolution and on database for search indices.
- Routes bind controller actions to named endpoints.
- Views depend on configuration arrays for product types and size chart columns.

```mermaid
graph LR
Routes["web.php"] --> PPC["PartnerProductController"]
Routes --> PBC["PartnerBulkController"]
PPC --> Product["Product"]
PPC --> Variant["ProductVariant"]
PBC --> Product
Product --> Storage["Storage"]
Views["create.blade.php / edit.blade.php"] --> Config["catalog.php"]
Search["SearchController"] --> Product
```

**Diagram sources**
- [web.php:119-167](file://routes/web.php#L119-L167)
- [PartnerProductController.php:14-337](file://app/Http/Controllers/Partner/PartnerProductController.php#L14-L337)
- [PartnerBulkController.php:10-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L10-L75)
- [Product.php:9-132](file://app/Models/Product.php#L9-L132)
- [ProductVariant.php:6-23](file://app/Models/ProductVariant.php#L6-L23)
- [create.blade.php:1-300](file://resources/views/partner/products/create.blade.php#L1-L300)
- [edit.blade.php:1-274](file://resources/views/partner/products/edit.blade.php#L1-L274)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)
- [SearchController.php:8-56](file://app/Http/Controllers/SearchController.php#L8-L56)

**Section sources**
- [web.php:119-167](file://routes/web.php#L119-L167)
- [PartnerProductController.php:14-337](file://app/Http/Controllers/Partner/PartnerProductController.php#L14-L337)
- [PartnerBulkController.php:10-75](file://app/Http/Controllers/Partner/PartnerBulkController.php#L10-L75)
- [Product.php:9-132](file://app/Models/Product.php#L9-L132)
- [ProductVariant.php:6-23](file://app/Models/ProductVariant.php#L6-L23)
- [create.blade.php:1-300](file://resources/views/partner/products/create.blade.php#L1-L300)
- [edit.blade.php:1-274](file://resources/views/partner/products/edit.blade.php#L1-L274)
- [catalog.php:13-70](file://config/catalog.php#L13-L70)
- [SearchController.php:8-56](file://app/Http/Controllers/SearchController.php#L8-L56)

## Performance Considerations
- Fulltext search indexing improves query performance for product name, brand, and description.
- Storing image_path enables efficient retrieval via storage URLs.
- Batch operations (bulk update/delete) reduce repeated round-trips by operating on collections.
- Unique constraints on product variants prevent duplicate sizes per product.

[No sources needed since this section provides general guidance]

## Troubleshooting Guide
- Image upload failures: Ensure file type is image and within size limits; confirm public disk write permissions.
- Slug conflicts: The controller regenerates slugs automatically; avoid submitting identical names across products.
- Variant mismatch: Enabling variants replaces all prior variants; ensure variant arrays are complete.
- Search not returning results: Verify database supports fulltext indexes; fallback LIKE queries require proper indexing.
- Status visibility: Only active, approved partner products appear in search results.

**Section sources**
- [PartnerProductController.php:78-86](file://app/Http/Controllers/Partner/PartnerProductController.php#L78-L86)
- [PartnerProductController.php:280-290](file://app/Http/Controllers/Partner/PartnerProductController.php#L280-L290)
- [SearchController.php:15-22](file://app/Http/Controllers/SearchController.php#L15-L22)
- [2026_07_01_100007_add_seo_and_search_to_products.php:18-28](file://database/migrations/2026_07_01_100007_add_seo_and_search_to_products.php#L18-L28)

## Conclusion
The partner product module provides a robust, configurable system for managing listings, variants, media, SEO, and bulk operations. Its design emphasizes clear separation of concerns, strong validation, and extensibility through configuration and database schema.

[No sources needed since this section summarizes without analyzing specific files]

## Appendices

### Best Practices
- Photography: Use consistent lighting, neutral backgrounds, and multiple angles. Include size model shots and close-ups for textures.
- Descriptions: Be precise about materials, care instructions, and unique features. Include origin stories where applicable.
- Inventory Organization: Keep SKUs/size consistency, maintain accurate stock counts, and regularly audit variants.
- SEO: Craft concise, keyword-rich titles and descriptions; leverage meta keywords thoughtfully.

[No sources needed since this section provides general guidance]