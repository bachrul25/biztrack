# Testing BizTrack

## Local dev setup (fresh VM)
```bash
composer install
cp .env.example .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
touch database/database.sqlite
php artisan key:generate --force
php artisan migrate:fresh --seed --force
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=8001 &
```

Seed produces: 10 produk, 59 sales, 67 finances, 2 users.

## Demo accounts
- Admin: `admin@biztrack.com` / `password` — full operational access.
- Owner: `owner@biztrack.com` / `password` — dashboard + 4 reports only. Direct URL to `/products`, `/sales`, `/stocks`, `/finances` returns HTTP 403 via `RoleMiddleware`.

## Key business rules to test
- **Sale atomically reduces stock and creates Finance income row** — `SaleRepository::createSale` wraps everything in `DB::transaction` and locks Product rows.
- **Stock cannot go negative** — insufficient stock throws `RuntimeException("Stok produk X tidak mencukupi (sisa N).")`, transaction rolls back.
- **Invoice pattern:** `INV-YYYYMMDD-####` (auto-generated per day).
- **Finance income source** for sales auto-entries is literally the string `"Penjualan"`.

## Driving Livewire forms reliably
BizTrack's sale modal uses native HTML `<select>` for product choice. Clicking `<option>` elements by coordinate via the desktop `computer` tool is unreliable because native select dropdowns render in a non-DOM overlay. Prefer Playwright over CDP for form interactions:

```python
from playwright.sync_api import sync_playwright
with sync_playwright() as p:
    browser = p.chromium.connect_over_cdp("http://localhost:29229")
    page = browser.contexts[0].pages[0]
    page.bring_to_front()
    # select_option works with native selects + triggers Livewire's change handler
    page.locator("select[wire\\:model\\.live$='product_id']").first.select_option(value="5")
    page.locator("input[wire\\:model\\.live$='quantity']").first.fill("3")
    page.get_by_role("button", name="Proses Transaksi").click()
```

The on-screen browser will visibly update, so recording still captures the flow.

## Verifying DB state outside UI
For assertions, run tinker rather than scraping the UI (UI sometimes shows stale counts for ~1s after Livewire errors):
```bash
php artisan tinker --execute="
  echo \App\Models\Product::where('name','Donat')->value('stock').' '.
       \App\Models\Sale::count().' '.
       \App\Models\Finance::where('type','income')->sum('amount');
"
```

## PDF verification
```bash
sudo apt-get install -y poppler-utils
pdftotext /home/ubuntu/Downloads/laporan-*.pdf -
```
PDFs must contain `BizTrack`, `Toko Kue Bu Nina`, `Periode:`, `Dicetak:`, and a `Total (N transaksi) Rp X.XXX.XXX` footer.
