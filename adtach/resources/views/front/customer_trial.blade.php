@extends('layout.index')
@extends('front.nav')
@section('home')
    {{-- search customer details --}}
    <div class="w- full h-[80px] flex justify-center place-items-center bg-[#1D4ED8] ">
        <input type="text" name="" onkeyup="searchTable()" id="searchInput" placeholder="Search Customer"
            class="w-[50%] py-2 px-3 outline-none border-0 rounded">
    </div>
    {{-- search customer details --}}




    {{-- Show Customer Details --}}

    <div class="w-[90%] mx-auto mt-5 mb-5">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border border-gray-300">S.NO</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NAME</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NUMBER</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER EMAIL</th>
                    <th class="px-4 py-2 border border-gray-300">PRICE</th>
                    <th class="px-4 py-2 border border-gray-300">REMARKS</th>
                    <th class="px-4 py-2 border border-gray-300">STATUS</th>
                    <th class="px-4 py-2 border border-gray-300">AGENT NAME</th>
                    <th class="px-4 py-2 border border-gray-300">DATE</th>
                    <th class="px-4 py-2 border border-gray-300">Action</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($customers as $index => $customer)
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $index + 1 }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $customer->customer_name }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $customer->customer_number }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $customer->customer_email }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">${{ $customer->price }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $customer->remarks }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer"><span
                                class="bg-danger py-1 px-2 rounded font-bold text-xl text-white"> {{ $customer->status }}
                            </span></td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $user->name }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            {{ \Carbon\Carbon::parse($customer->created_at)->format('d M, Y') }}</td>
                        @if ($customer->status === 'lead')
                            <form action="{{ route('customerStatus', $customer->id) }}" method="POST">
                                @csrf
                                <td class="flex gap-1 mt-4">
                                    <input type="hidden" name="status" id="input">
                                    <button class="btn btn-success" id="statusBtn">sale</button>
                                    <button class="btn btn-danger" id="statusBtn">trial</button>
                                </td>
                            </form>
                        @elseif($customer->status === 'trial')
                            <form action="{{ route('customerStatus', $customer->id) }}" method="POST">
                                @csrf
                                <td class="flex gap-1 mt-4">
                                    <input type="hidden" name="status" id="input">
                                    <button class="btn btn-warning" id="statusBtn">lead</button>
                                    <button class="btn btn-success" id="statusBtn">sale</button>
                                </td>
                            </form>
                        @else
                        @endif
                    </tr>
                @endforeach
                @if ($customers->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No Trial Record Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Show Customer Details --}}


    <script>
        function searchTable() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const tableBody = document.getElementById("tableBody");
            console.log(tableBody);
            const rows = tableBody.getElementsByTagName("tr");
            console.log(rows);

            for (let i = 0; i < rows.length; i++) {
                let customerName = rows[i].getElementsByTagName("td")[1].textContent.toLowerCase();
                let customerNumber = rows[i].getElementsByTagName("td")[2].textContent.toLowerCase();
                console.log(customerNumber);
                if (customerName.includes(searchInput) || customerNumber.includes(searchInput)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none"; // Hide rows that don't match
                }
            }
        }

        let statusInput = document.querySelectorAll('#input');
        let statusBtn = document.querySelectorAll('#statusBtn');

        statusBtn.forEach((btn) => {
            btn.addEventListener('click', function(e) {
                target = e.target;
                text = target.textContent;

                statusInput.forEach((input) => {
                    input.value = text;
                })

            });
        });
    </script>
@endsection