<!-- Modal -->
<div id="invoiceModal" class="fixed inset-0 flex items-center justify-center  hidden">
        <div class="bg-white rounded-lg shadow-lg w-3/4">
            <div class="p-4 border-b flex justify-between items-center">
                <h5 class="text-lg font-semibold">Invoice Details</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" onclick="closeModal()">&times;</button>
            </div>
            <div class="p-6">
                <!-- Customer Info -->
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Customer Name</th>
                            <th class="border p-2">Customer Email</th>
                            <th class="border p-2">Customer ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-2" id="customerName"></td>
                            <td class="border p-2" id="customerEmail"></td>
                            <td class="border p-2" id="customerId"></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Invoice Info -->
                <table class="w-full border-collapse border border-gray-300 mt-4">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Invoice Total</th>
                            <th class="border p-2">VAT</th>
                            <th class="border p-2">Discount</th>
                            <th class="border p-2">Payable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-2">$<span id="invoiceTotal"></span></td>
                            <td class="border p-2">$<span id="invoiceVat"></span></td>
                            <td class="border p-2">$<span id="invoiceDiscount"></span></td>
                            <td class="border p-2">$<span id="invoicePayable"></span></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Products List -->
                <h4 class="mt-4 font-medium">Products:</h4>
                <table class="w-full border-collapse border border-gray-300 mt-2">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Product Name</th>
                            <th class="border p-2">Quantity</th>
                            <th class="border p-2">Price</th>
                        </tr>
                    </thead>
                    <tbody id="productsList">
                        <!-- Dynamic product items will be inserted here -->
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button onclick="printInvoice()" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Print</button>
                <button type="button" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
