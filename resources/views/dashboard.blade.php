<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center bg-gradient-to-r from-blue-500 to-purple-500 p-6 rounded-lg shadow-lg">
                        <span class="text-5xl font-extrabold text-white animate-pulse">Ksh {{ Auth::user()->balance }}</span>
                    </div>
                    <div id="highestTransactionAlert"
                        class="mt-2 p-4 bg-red-100 border border-red-400 text-red-700 rounded hidden text-center font-bold">
                    </div>
                    <div class="mt-6">
                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="flex-1">
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                        Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="flex-1">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End
                                        Date</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="flex-1">
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                    <select name="reason" id="reason"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all" selected disabled>All Reasons</option>
                                        @foreach ($distinctReasons as $distinctReason)
                                            <option value="{{ $distinctReason }}">{{ $distinctReason }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" id="filterButton"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="filteredResults"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function fetchandrender(start_date, end_date, reason) {
        const searchParams = new URLSearchParams({
            start_date: start_date,
            end_date: end_date,
            reason: reason
        });

        fetch(`dashboard/filter?${searchParams.toString()}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('filteredResults');
                resultsDiv.innerHTML = ''; // Clear previous results

                // Create a canvas element for the chart
                const canvas = document.createElement('canvas');
                resultsDiv.appendChild(canvas);

                // Prepare data for the chart
                const transactions = data.transactions;
                const labels = transactions.map(transaction => transaction.date);
                const values = transactions.map(transaction => transaction.amount);

                // Check if any transaction includes a reason
                const includeReason = transactions.some(transaction => transaction.reason !== undefined);

                // Create the bar chart
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Transaction Amount',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.raw;

                                        // Add reason to the tooltip if it exists
                                        const transaction = transactions[context.dataIndex];
                                        if (includeReason && transaction.reason) {
                                            label += ` (Reason: ${transaction.reason})`;
                                        }

                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });

                // Display highest transaction if available
                if (data.highest_transaction) {
                    // Display highest transaction alert
                    const alertDiv = document.getElementById('highestTransactionAlert');
                    if (data.highest_transaction) {
                        const today = new Date().toISOString().split('T')[0];
                        const transactionDate = new Date(data.highest_transaction.date).toISOString().split('T')[0];

                        let message = '';
                        if (transactionDate === today) {
                            message =
                                `Today you've spent most on ${data.highest_transaction.reason} (${data.highest_transaction.amount})`;
                        } else {
                            message =
                                `On ${data.highest_transaction.date} you spent most on ${data.highest_transaction.reason}  (${data.highest_transaction.amount})`;
                        }

                        alertDiv.textContent = message;
                        alertDiv.classList.remove('hidden');
                    } else {
                        alertDiv.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }
    // Function to get the first and last day of the current month
    function getCurrentMonthDates() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        return {
            start: firstDay.toISOString().split('T')[0],
            end: lastDay.toISOString().split('T')[0]
        };
    }

    // Function to fetch and render data
    document.getElementById('filterButton').addEventListener('click', function() {
        const start_date = document.getElementById('start_date').value || getCurrentMonthDates().start;
        const end_date = document.getElementById('end_date').value || getCurrentMonthDates().end;
        const reason = document.getElementById('reason').value;

        fetchandrender(start_date, end_date, reason)
    });

    // Fetch and display current month's data on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentMonth = getCurrentMonthDates();
        const reason = 'all';
        console.log(currentMonth);
        fetchandrender(currentMonth.start, currentMonth.end, reason)
    });
</script>
</script>
