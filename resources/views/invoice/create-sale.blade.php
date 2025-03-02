@extends('layout.sidebar')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <span class="text-bold text-dark">INVOICE </span>
                            <p class="text-bold mx-0 my-1 text-dark">Invoice</p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <table class="table w-100" id="invoiceTable">
                        <thead>
                            <tr class="text-xs">
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceList"></tbody>
                    </table>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                    <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                    <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                    <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                    <label>Discount(%):</label>
                    <input type="number" id="discountP" class="form-control w-40" value="0" min="0" step="0.25" onchange="DiscountChange()"/>
                    <button onclick="createInvoice()" class="btn my-3 bg-gradient-primary w-40">Confirm</button>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table w-100" id="productTable">
                        <thead>
                            <tr class="text-xs text-bold">
                                <th>Product</th>
                                <th>Pick</th>
                            </tr>
                        </thead>
                        <tbody id="productList"></tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead>
                            <tr class="text-xs text-bold">
                                <th>Customer</th>
                                <th>Pick</th>
                            </tr>
                        </thead>
                        <tbody id="customerList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        let InvoiceItemList = [];

        function ShowInvoiceItem() {
            let invoiceList = $('#invoiceList');
            invoiceList.empty();
            InvoiceItemList.forEach(function (item, index) {
                let row = `<tr class="text-xs">
                            <td>${item['product_name']}</td>
                            <td>${item['qty']}</td>
                            <td>${item['sale_price']}</td>
                            <td><button data-index="${index}" class="btn btn-danger btn-sm remove">Remove</button></td>
                         </tr>`;
                invoiceList.append(row);
            });
            CalculateGrandTotal();
            $('.remove').on('click', function () {
                let index = $(this).data('index');
                removeItem(index);
            });
        }

        function removeItem(index) {
            InvoiceItemList.splice(index, 1);
            ShowInvoiceItem();
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }

        function CalculateGrandTotal() {
            let Total = 0;
            let Vat = 0;
            let Payable = 0;
            let Discount = 0;
            let discountPercentage = parseFloat($('#discountP').val());
            InvoiceItemList.forEach(item => {
                Total += parseFloat(item['sale_price']);
            });
            if (discountPercentage > 0) {
                Discount = ((Total * discountPercentage) / 100).toFixed(2);
                Total -= Discount;
            }
            Vat = ((Total * 5) / 100).toFixed(2);
            Payable = (parseFloat(Total) + parseFloat(Vat)).toFixed(2);
            $('#total').text(Total);
            $('#payable').text(Payable);
            $('#vat').text(Vat);
            $('#discount').text(Discount);
        }

        function add() {
            let PId = $('#PId').val();
            let PName = $('#PName').val();
            let PPrice = $('#PPrice').val();
            let PQty = $('#PQty').val();
            let PTotalPrice = (parseFloat(PPrice) * parseFloat(PQty)).toFixed(2);
            if (!PId || !PName || !PPrice || !PQty) {
                alert("All fields are required");
                return;
            }
            let item = { product_name: PName, product_id: PId, qty: PQty, sale_price: PTotalPrice };
            InvoiceItemList.push(item);
            $('#create-modal').modal('hide');
            ShowInvoiceItem();
        }
    </script>
@endsection
