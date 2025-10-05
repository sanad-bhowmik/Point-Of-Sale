<div class="p-2">
    <input type="text" id="sidebar-search" class="form-control" placeholder="Search menu...">
</div>

<style>
    .c-sidebar-nav-link {
        padding: 8px 12px !important;
        font-size: 12px !important;
    }

    .c-sidebar {
        width: 200px !important;
    }

    .c-wrapper {
        margin-left: 200px !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("sidebar-search");
        const navItems = document.querySelectorAll(".c-sidebar-nav-item");

        searchInput.addEventListener("keyup", function() {
            let filter = searchInput.value.toLowerCase();

            navItems.forEach(function(item) {
                let text = item.textContent.toLowerCase();
                let matches = text.includes(filter);

                if (matches) {
                    item.style.display = "";
                    // expand parent dropdown if hidden item found inside
                    let parent = item.closest(".c-sidebar-nav-dropdown");
                    if (parent) {
                        parent.classList.add("c-show");
                        parent.style.display = "";
                    }
                } else {
                    item.style.display = "none";
                }
            });
        });
    });
</script>

<li class="c-sidebar-nav-item {{ request()->routeIs('home') ? 'c-active' : '' }}">
    <a class="c-sidebar-nav-link" href="{{ route('home') }}">
        <i class="c-sidebar-nav-icon bi bi-house" style="line-height: 1;"></i> Home
    </a>
</li>

@can('access_products')

<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('products.*') || request()->routeIs('product-categories.*') ? 'c-show' : '' }}" id="products">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-journal-bookmark" style="line-height: 1;"></i> Products
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        @can('access_product_categories')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('product-categories.*') ? 'c-active' : '' }}"
                href="{{ route('product-categories.index') }}">
                <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i> Categories
            </a>
        </li>
        @endcan
        @can('create_products')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('products.create') ? 'c-active' : '' }}"
                href="{{ route('products.create') }}">
                <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Product
            </a>
        </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('products.index') ? 'c-active' : '' }}"
                href="{{ route('products.index') }}">
                <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Products
            </a>
        </li>
        {{-- @can('print_barcodes')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('barcode.print') ? 'c-active' : '' }}"
        href="{{ route('barcode.print') }}">
        <i class="c-sidebar-nav-icon bi bi-printer" style="line-height: 1;"></i> Print Barcode
        </a>
</li>
@endcan --}}

@can('create_sizes')
<!-- Sizes Submenu -->
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('product.size.*') ? 'c-show' : '' }}" id="sizes">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-box" style="line-height: 1;"></i> Sizes
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('product.size.create') ? 'c-active' : '' }}"
                href="{{ route('product.size.create') }}">
                Create Sizes
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('product.size.view') ? 'c-active' : '' }}"
                href="{{ route('product.size.view') }}">
                View Sizes
            </a>
        </li>
    </ul>
</li>
@endcan

</ul>
</li>
@endcan


{{-- @can('access_adjustments')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown d-none {{ request()->routeIs('adjustments.*') ? 'c-show' : '' }}">
<a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
    <i class="c-sidebar-nav-icon bi bi-clipboard-check" style="line-height: 1;"></i> Stock Adjustments
</a>
<ul class="c-sidebar-nav-dropdown-items">
    @can('create_adjustments')
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.create') ? 'c-active' : '' }}"
            href="{{ route('adjustments.create') }}">
            <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Adjustment
        </a>
    </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.index') ? 'c-active' : '' }}"
            href="{{ route('adjustments.index') }}">
            <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Adjustments
        </a>
    </li>
</ul>
</li>
@endcan --}}

{{-- @can('access_quotations')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown  d-none {{ request()->routeIs('quotations.*') ? 'c-show' : '' }}">
<a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
    <i class="c-sidebar-nav-icon bi bi-cart-check" style="line-height: 1;"></i> Quotations
</a>
<ul class="c-sidebar-nav-dropdown-items">
    @can('create_adjustments')
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.create') ? 'c-active' : '' }}"
            href="{{ route('quotations.create') }}">
            <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Quotation
        </a>
    </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.index') ? 'c-active' : '' }}"
            href="{{ route('quotations.index') }}">
            <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Quotations
        </a>
    </li>
</ul>
</li>
@endcan --}}

{{-- @can('access_purchases')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('purchases.*') || request()->routeIs('purchase-payments*') ? 'c-show' : '' }}">
<a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
    <i class="c-sidebar-nav-icon bi bi-bag" style="line-height: 1;"></i> Purchases
</a>
@can('create_purchase')
<ul class="c-sidebar-nav-dropdown-items">
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.create') ? 'c-active' : '' }}"
            href="{{ route('purchases.create') }}">
            <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Purchase
        </a>
    </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.index') ? 'c-active' : '' }}"
            href="{{ route('purchases.index') }}">
            <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Purchases
        </a>
    </li>
</ul>
</li>
@endcan --}}

{{-- @can('access_purchase_returns')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown  d-none {{ request()->routeIs('purchase-returns.*') || request()->routeIs('purchase-return-payments.*') ? 'c-show' : '' }}">
<a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
    <i class="c-sidebar-nav-icon bi bi-arrow-return-right" style="line-height: 1;"></i> Purchase Returns
</a>
<ul class="c-sidebar-nav-dropdown-items">
    @can('create_purchase_returns')
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.create') ? 'c-active' : '' }}"
            href="{{ route('purchase-returns.create') }}">
            <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Purchase Return
        </a>
    </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.index') ? 'c-active' : '' }}"
            href="{{ route('purchase-returns.index') }}">
            <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Purchase Returns
        </a>
    </li>
</ul>
</li>
@endcan --}}

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-costing.index') ? 'c-show' : '' }}" id="costing">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-currency-exchange" style="line-height: 1;"></i> LC Costing
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('costing.addCosting') ? 'c-active' : '' }}"
                href="{{ route('costing.addCosting') }}">
                <i class="c-sidebar-nav-icon bi bi-arrows-fullscreen" style="line-height: 1;"></i> Add Costing
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('costing.viewCosting') ? 'c-active' : '' }}"
                href="{{ route('costing.viewCosting') }}">
                <i class="c-sidebar-nav-icon bi bi-bar-chart" style="line-height: 1;"></i> Costing List
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-container.index') ? 'c-show' : '' }}" id="container">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-calendar3-range" style="line-height: 1;"></i> Container
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('container.view') ? 'c-active' : '' }}"
                href="{{ route('container.view') }}">
                <i class="c-sidebar-nav-icon bi bi-cloud-plus" style="line-height: 1;"></i> Add Container
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('container.containerTbl') ? 'c-active' : '' }}"
                href="{{ route('container.containerTbl') }}">
                <i class="c-sidebar-nav-icon bi bi-bar-chart" style="line-height: 1;"></i> Container List
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_sales')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sales.*') || request()->routeIs('sale-payments*') ? 'c-show' : '' }}" id="sales">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-receipt" style="line-height: 1;"></i> Sales
    </a>
    @can('create_sales')
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('sales.create') ? 'c-active' : '' }}"
                href="{{ route('sales.create') }}">
                <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Sale
            </a>
        </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('sales.index') ? 'c-active' : '' }}"
                href="{{ route('sales.index') }}">
                <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Sales
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*seasonalfruit.index') ? 'c-show' : '' }}" id="seasonal">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-basket3-fill" style="line-height: 1;"></i> Seasonal Fruit
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('seasonalfruit.create') ? 'c-active' : '' }}"
                href="{{ route('seasonalfruit.create') }}">
                <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Seasonal Fruit
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('seasonalfruit.show') ? 'c-active' : '' }}"
                href="{{ route('seasonalfruit.show') }}">
                <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> Seasonal Fruit
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-bank.index') ? 'c-show' : '' }}" id="banks">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-bank" style="line-height: 1;"></i> Banks
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('bank.create') ? 'c-active' : '' }}"
                href="{{ route('bank.create') }}">
                <i class="c-sidebar-nav-icon bi bi-arrows-fullscreen" style="line-height: 1;"></i> Add Bank
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('bank.index') ? 'c-active' : '' }}"
                href="{{ route('bank.index') }}">
                <i class="c-sidebar-nav-icon bi bi-bar-chart" style="line-height: 1;"></i> Bank List
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-transaction.index') ? 'c-show' : '' }}" id="transactions">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-wallet2" style="line-height: 1;"></i> Transactions
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('transaction.create') ? 'c-active' : '' }}"
                href="{{ route('transaction.create') }}">
                <i class="c-sidebar-nav-icon bi bi-arrows-fullscreen" style="line-height: 1;"></i> Add Transaction
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('transaction.index') ? 'c-active' : '' }}"
                href="{{ route('transaction.index') }}">
                <i class="c-sidebar-nav-icon bi bi-bar-chart" style="line-height: 1;"></i> Transaction List
            </a>
        </li>
        {{-- <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('transaction.ledger') ? 'c-active' : '' }}"
        href="{{ route('transaction.ledger') }}">
        <i class="c-sidebar-nav-icon bi bi-journal-text" style="line-height: 1;"></i> Bank Ledger
        </a>
</li> --}}
<li class="c-sidebar-nav-item">
    <a class="c-sidebar-nav-link {{ request()->routeIs('transaction.bank_report') ? 'c-active' : '' }}"
        href="{{ route('transaction.bank_report') }}">
        <i class="c-sidebar-nav-icon bi bi-journal-text" style="line-height: 1;"></i> Bank Ledger
    </a>
</li>
</ul>

</li>
@endcan

{{-- @can('access_sale_returns')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sale-returns.*') || request()->routeIs('sale-return-payments.*') ? 'c-show' : '' }}">
<a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
    <i class="c-sidebar-nav-icon bi bi-arrow-return-left" style="line-height: 1;"></i> Sale Returns
</a>
@can('create_sale_returns')
<ul class="c-sidebar-nav-dropdown-items">
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.create') ? 'c-active' : '' }}"
            href="{{ route('sale-returns.create') }}">
            <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> Create Sale Return
        </a>
    </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.index') ? 'c-active' : '' }}"
            href="{{ route('sale-returns.index') }}">
            <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> All Sale Returns
        </a>
    </li>
</ul>
</li>
@endcan --}}

@can('access_expenses')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'c-show' : '' }}" id="expenses">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-arrow-repeat" style="line-height: 1;"></i> Expenses
    </a>
    <ul class="c-sidebar-nav-dropdown-items">


        <!-- Product Expense Submenu -->
        @can('create_office_expense')
        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('office_expense.*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon bi bi-box" style="line-height: 1;"></i> Product Expense
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('access_expense_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expense-categories.*') ? 'c-active' : '' }}"
                        href="{{ route('expense-categories.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i> Categories
                    </a>
                </li>
                @endcan
                @can('access_expense_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expense-names.*') ? 'c-active' : '' }}"
                        href="{{ route('expense-names.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i>Sub Categories
                    </a>
                </li>
                @endcan
                @can('create_expenses')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.create') ? 'c-active' : '' }}"
                        href="{{ route('expenses.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i>Expense Posting
                    </a>
                </li>
                @endcan
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.index') ? 'c-active' : '' }}"
                        href="{{ route('expenses.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i>Expenses History
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expense.expenseLedger') ? 'c-active' : '' }}"
                        href="{{ route('expense.expenseLedger') }}">
                        <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> Expenses Ledger
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        <!-- Office Expense Submenu -->
        @can('create_office_expense')
        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('office_expense.*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon bi bi-box" style="line-height: 1;"></i> Office Expense
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.name') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.name') }}">
                        Categories
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.create') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.create') }}">
                        Expense Posting
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.history') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.history') }}">
                        Cash In History
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.history') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.history') }}">
                        Cash In History
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.view') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.view') }}">
                       Expense History
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.ledger') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.ledger') }}">
                      Expense Ledger
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('office_expense.ledger') ? 'c-active' : '' }}"
                        href="{{ route('office_expense.ledger') }}">
                        Office Expense Ledger
                    </a>
                </li>
            </ul>
        </li>
        @endcan

    </ul>
</li>
@endcan

@can('access_customers|access_suppliers')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('customers.*') || request()->routeIs('suppliers.*') ? 'c-show' : '' }}" id="parties">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-people" style="line-height: 1;"></i> Parties
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        @can('access_customers')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('customers.*') ? 'c-active' : '' }}"
                href="{{ route('customers.index') }}">
                <i class="c-sidebar-nav-icon bi bi-people-fill" style="line-height: 1;"></i> Customers
            </a>
        </li>
        @endcan
        @can('access_suppliers')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('suppliers.*') ? 'c-active' : '' }}"
                href="{{ route('suppliers.index') }}">
                <i class="c-sidebar-nav-icon bi bi-people-fill" style="line-height: 1;"></i> Suppliers
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-transaction.index') ? 'c-show' : '' }}" id="permit">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-blockquote-right" style="line-height: 1;"></i> Import Permit
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('input_permit.create') ? 'c-active' : '' }}"
                href="{{ route('input_permit.create') }}">
                <i class="c-sidebar-nav-icon bi bi-arrows-fullscreen" style="line-height: 1;"></i> Add Permit
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('input_permit.view') ? 'c-active' : '' }}"
                href="{{ route('input_permit.view') }}">
                <i class="c-sidebar-nav-icon bi bi-bar-chart" style="line-height: 1;"></i> Import Permit List
            </a>
        </li>

    </ul>

</li>
@endcan

@can('access_reports')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-report.index') ? 'c-show' : '' }}" id="reports">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-graph-up" style="line-height: 1;"></i> Reports
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('stock-report.index') ? 'c-active' : '' }}"
                href="{{ route('stock-report.index') }}">
                <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> Stock Report
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('buying-selling-report.index') ? 'c-active' : '' }}"
                href="{{ route('buying-selling-report.index') }}">
                <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> Buying and Selling Report
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('shipment-status-report.index') ? 'c-active' : '' }}"
                href="{{ route('shipment-status-report.index') }}">
                <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> Shipment Status Report
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('cash-flow-report.index') ? 'c-active' : '' }}"
                href="{{ route('cash-flow-report.index') }}">
                <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> Details of cash flow
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('expense.finalReport') ? 'c-active' : '' }}"
                href="{{ route('expense.finalReport') }}">
                <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> Final Report
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_user_management')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('roles*') ? 'c-show' : '' }}" id="management">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-people" style="line-height: 1;"></i> User Management
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('users.create') ? 'c-active' : '' }}"
                href="{{ route('users.create') }}">
                <i class="c-sidebar-nav-icon bi bi-person-plus" style="line-height: 1;"></i> Create User
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('users*') ? 'c-active' : '' }}"
                href="{{ route('users.index') }}">
                <i class="c-sidebar-nav-icon bi bi-person-lines-fill" style="line-height: 1;"></i> All Users
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('roles*') ? 'c-active' : '' }}"
                href="{{ route('roles.index') }}">
                <i class="c-sidebar-nav-icon bi bi-key" style="line-height: 1;"></i> Roles & Permissions
            </a>
        </li>
    </ul>
</li>
@endcan

@can('access_currencies|access_settings')
<li
    class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('currencies*') || request()->routeIs('units*') ? 'c-show' : '' }}" id="settings">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <i class="c-sidebar-nav-icon bi bi-gear" style="line-height: 1;"></i> Settings
    </a>
    @can('access_units')
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('units*') ? 'c-active' : '' }}"
                href="{{ route('units.index') }}">
                <i class="c-sidebar-nav-icon bi bi-calculator" style="line-height: 1;"></i> Units
            </a>
        </li>
    </ul>
    @endcan
    @can('access_currencies')
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('currencies*') ? 'c-active' : '' }}"
                href="{{ route('currencies.index') }}">
                <i class="c-sidebar-nav-icon bi bi-cash-stack" style="line-height: 1;"></i> Currencies
            </a>
        </li>
    </ul>
    @endcan
    @can('access_settings')
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('settings*') ? 'c-active' : '' }}"
                href="{{ route('settings.index') }}">
                <i class="c-sidebar-nav-icon bi bi-sliders" style="line-height: 1;"></i> System Settings
            </a>
        </li>
    </ul>
    @endcan
</li>
@endcan

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.querySelector(".c-sidebar-nav");
        const dropdowns = document.querySelectorAll(".c-sidebar-nav-dropdown");
        const activeLink = document.querySelector(".c-active");
        localStorage.setItem("sidebarOpenDropdowns", JSON.stringify([]));
        localStorage.setItem("sidebarScroll", 0);
        const savedScroll = localStorage.getItem("sidebarScroll");
        if (savedScroll && sidebar) {
            sidebar.scrollTop = parseInt(savedScroll);
        }

        const openDropdowns = JSON.parse(localStorage.getItem("sidebarOpenDropdowns") || "[]");
        dropdowns.forEach(dropdown => {
            const id = dropdown.getAttribute("id");
            if (id && openDropdowns.includes(id)) {
                dropdown.classList.add("c-show");
            }
        });

        if (!savedScroll && activeLink && sidebar) {
            sidebar.scrollTop = activeLink.offsetTop - 50;
        }

        sidebar.addEventListener("scroll", function() {
            localStorage.setItem("sidebarScroll", sidebar.scrollTop);
        });

        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector(".c-sidebar-nav-dropdown-toggle");
            if (toggle) {
                toggle.addEventListener("click", function() {
                    const openDropdowns = [];
                    dropdowns.forEach(d => {
                        if (d.classList.contains("c-show") && d.getAttribute("id")) {
                            openDropdowns.push(d.getAttribute("id"));
                        }
                    });
                    localStorage.setItem("sidebarOpenDropdowns", JSON.stringify(openDropdowns));
                });
            }
        });
    });
</script>
