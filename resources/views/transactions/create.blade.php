<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            @if ($errors->count() > 1)
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>{{ $errors->first() }}</p>
                            @endif
                        </div>
                    @endif
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                id="amount" name="amount" step="0.01" placeholder="Amount"
                                value="{{ old('amount') }}">
                        </div>
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                id="date" name="date" placeholder="Date" value="{{ old('date') }}">
                        </div>
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                id="reason" name="reason">
                                <option value="" selected disabled>Select a reason</option>
                                <option value="lunch" {{ old('reason') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                                <option value="transport" {{ old('reason') == 'transport' ? 'selected' : '' }}>Transport
                                </option>
                                <option value="snacks" {{ old('reason') == 'snacks' ? 'selected' : '' }}>Snacks</option>
                                <option value="tcost" {{ old('reason') == 'tcost' ? 'selected' : '' }}>Transaction
                                    Cost</option>
                                <option value="Airtime-and-Bundles"
                                    {{ old('reason') == 'Airtime-and-Bundles' ? 'selected' : '' }}>Airtime & Bundles
                                </option>
                                <option value="other-business"
                                    {{ old('reason') == 'other-business' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="transaction_type" class="block text-sm font-medium text-gray-700">Transaction
                                Type</label>
                            <select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                id="transaction_type" name="transaction_type">
                                <option value="" selected disabled>Select a transaction type</option>
                                <option value="SM" {{ old('transaction_type') == 'SM' ? 'selected' : '' }}>
                                    Send Money</option>
                                <option value="PB" {{ old('transaction_type') == 'PB' ? 'selected' : '' }}>
                                    Paybills</option>
                            </select>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-64 text-center">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
