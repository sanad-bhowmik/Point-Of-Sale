<!-- Include jsPDF library from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<div class="btn-group dropleft">
    <button type="button" class="btn btn-ghost-primary dropdown rounded" data-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
        <!-- POS Invoice button generating PDF and opening in new tab -->
        <button type="button" class="dropdown-item"
            onclick="generateInvoice({
                reference: '{{ $data->reference }}',
                customer_name: '{{ $data->customer_name }}',
                total_amount: '{{ $data->total_amount }}',
                paid_amount: '{{ $data->paid_amount }}',
                due_amount: '{{ $data->due_amount }}',
                lc: '{{ $data->lc ? $data->lc->lc_name : '-' }}',
                container: '{{ $data->container ? $data->container->name : '-' }}',
                date: '{{ $data->date }}'
            })">
            <i class="bi bi-file-earmark-pdf mr-2 text-success" style="line-height: 1;"></i> POS Invoice
        </button>

        @can('access_sale_payments')
        <a href="{{ route('sale-payments.index', $data->id) }}" class="dropdown-item">
            <i class="bi bi-cash-coin mr-2 text-warning" style="line-height: 1;"></i> Show Payments
        </a>
        @endcan

        @can('access_sale_payments')
        @if($data->due_amount > 0)
        <a href="{{ route('sale-payments.create', $data->id) }}" class="dropdown-item">
            <i class="bi bi-plus-circle-dotted mr-2 text-success" style="line-height: 1;"></i> Add Payment
        </a>
        @endif
        @endcan

        @can('edit_sales')
        <a href="{{ route('sales.edit', $data->id) }}" class="dropdown-item">
            <i class="bi bi-pencil mr-2 text-primary" style="line-height: 1;"></i> Edit
        </a>
        @endcan

        @can('show_sales')
        <a href="{{ route('sales.show', $data->id) }}" class="dropdown-item">
            <i class="bi bi-eye mr-2 text-info" style="line-height: 1;"></i> Details
        </a>
        @endcan

        @can('delete_sales')
        <button id="delete" class="dropdown-item" onclick="
                event.preventDefault();
                if (confirm('Are you sure? It will delete the data permanently!')) {
                document.getElementById('destroy{{ $data->id }}').submit()
                }">
            <i class="bi bi-trash mr-2 text-danger" style="line-height: 1;"></i> Delete
            <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('sales.destroy', $data->id) }}" method="POST">
                @csrf
                @method('delete')
            </form>
        </button>
        @endcan
    </div>
</div>

<script>
    function generateInvoice(data) {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF({
            unit: 'pt',
            format: 'a4'
        });

        const margin = 40;
        const pageWidth = doc.internal.pageSize.getWidth();
        let y = margin;

        // Format currency
        function formatCurrency(amount) {
            return  parseFloat(amount).toFixed(2) + '/ tk';
        }

        // --- Invoice Title ---
        doc.setFontSize(18);
        doc.setFont("helvetica", "bold");
        doc.text("COMMERCIAL INVOICE", pageWidth / 2, y, {
            align: "center"
        });
        y += 30;

        // --- Invoice Information Table ---
        const tableWidth = pageWidth - (margin * 2);
        const rowHeight = 18;
        const col1Width = 150; // th column width
        const col2Width = tableWidth - col1Width; // td column width

        // Table Header - INVOICE INFORMATION (th)
        doc.setFillColor(240, 240, 240);
        doc.rect(margin, y, tableWidth, rowHeight, 'F');
        doc.setDrawColor(0, 0, 0);
        doc.rect(margin, y, tableWidth, rowHeight);

        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.text("INVOICE INFORMATION", margin + 10, y + 12);
        y += rowHeight;

        // Invoice Details Rows with th and td
        const details = [{
                th: "Invoice Number",
                td: data.reference
            },
            {
                th: "Invoice Date",
                td: data.date
            },
            {
                th: "Customer Name",
                td: data.customer_name
            },
            {
                th: "LC Reference",
                td: data.lc
            },
            {
                th: "Container Number",
                td: data.container
            }
        ];

        details.forEach((detail, index) => {
            // Draw th cell (left column)
            doc.rect(margin, y, col1Width, rowHeight);
            doc.setFont("helvetica", "bold");
            doc.text(detail.th, margin + 10, y + 12);

            // Draw td cell (right column)
            doc.rect(margin + col1Width, y, col2Width, rowHeight);
            doc.setFont("helvetica", "normal");
            doc.text(detail.td, margin + col1Width + 10, y + 12);

            y += rowHeight;
        });

        y += 20;

        // --- Financial Summary Table ---
        // Table Header - FINANCIAL SUMMARY (th)
        doc.setFillColor(240, 240, 240);
        doc.rect(margin, y, tableWidth, rowHeight, 'F');
        doc.rect(margin, y, tableWidth, rowHeight);

        doc.setFont("helvetica", "bold");
        doc.text("FINANCIAL SUMMARY", margin + 10, y + 12);
        y += rowHeight;

        // Financial Rows with th and td
        const financialData = [{
                th: "Total Amount",
                td: formatCurrency(data.total_amount)
            },
            {
                th: "Paid Amount",
                td: formatCurrency(data.paid_amount)
            },
            {
                th: "Due Amount",
                td: formatCurrency(data.due_amount)
            }
        ];

        financialData.forEach((item, index) => {
            // Draw th cell (left column)
            doc.rect(margin, y, col1Width, rowHeight);
            doc.setFont("helvetica", "bold");
            doc.text(item.th, margin + 10, y + 12);

            // Draw td cell (right column)
            doc.rect(margin + col1Width, y, col2Width, rowHeight);
            doc.setFont("helvetica", "normal");
            doc.text(item.td, margin + col1Width + 10, y + 12);

            y += rowHeight;
        });

        y += 30;

        // --- Payment Status ---
        const dueAmount = parseFloat(data.due_amount);
        let paymentStatus = "";

        if (dueAmount === 0) {
            paymentStatus = "PAID IN FULL";
        } else if (dueAmount === parseFloat(data.total_amount)) {
            paymentStatus = "PENDING PAYMENT";
        } else {
            paymentStatus = "PARTIALLY PAID";
        }


        y += 40;

        // --- Terms and Conditions ---
        doc.setFont("helvetica", "bold");
        doc.setFontSize(10);
        doc.text("Terms & Conditions:", margin, y);
        y += 15;

        doc.setFont("helvetica", "normal");
        doc.setFontSize(8);
        const terms = [
            "1. Payment is due within 30 days of invoice date",
            "2. Late payments are subject to a 1.5% monthly interest charge",
            "3. All disputes must be submitted in writing within 15 days",
            "4. Goods remain company property until full payment is received"
        ];

        terms.forEach((term, index) => {
            doc.text(term, margin, y);
            y += 12;
        });

        y += 20;

        // --- Footer ---
        doc.setDrawColor(200, 200, 200);
        doc.line(margin, y, pageWidth - margin, y);
        y += 20;

        doc.setFontSize(9);
        doc.setFont("helvetica", "normal");
        doc.text("This is an computer-generated invoice. No signature is required.", pageWidth / 2, y, {
            align: "center"
        });
        y += 15;

        doc.text("Thank you for your business.", pageWidth / 2, y, {
            align: "center"
        });

        // --- Open PDF in new tab ---
        const pdfBlob = doc.output('bloburl');
        window.open(pdfBlob, '_blank');
    }
</script>
