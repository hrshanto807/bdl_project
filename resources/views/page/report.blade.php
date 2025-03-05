@extends('layout.sidebar')
@section('content')
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Sales Report</h4>
                <label class="block text-sm font-medium text-gray-700">Date From</label>
                <input id="FormDate" type="date" class="w-full p-2 border border-gray-300 rounded-lg mt-1"/>
                <label class="block text-sm font-medium text-gray-700 mt-3">Date To</label>
                <input id="ToDate" type="date" class="w-full p-2 border border-gray-300 rounded-lg mt-1"/>
                <button onclick="SalesReport()" class="w-full mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Download</button>
            </div>
        </div>
    </div>
@endsection

<script>
   async function SalesReport() {
        let FormDate = document.getElementById('FormDate').value;
        let ToDate = document.getElementById('ToDate').value;
        
        if (FormDate.length === 0 || ToDate.length === 0) {
            errorToast("Date Range Required!");
            return;
        }

        try {
            let response = await axios.get(`/sales-report/${FormDate}/${ToDate}`, HeaderTokenWithBlob());
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'SalesReport.pdf');
            document.body.appendChild(link);
            link.click();
            link.remove();
        } catch (error) {
            console.error("Error downloading sales report", error);
        }
    }
</script>
