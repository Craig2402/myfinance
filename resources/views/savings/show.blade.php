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
                    <h2 class="text-2xl font-semibold mb-4">Savings Target Information</h2>
                    <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                        <p><strong>Target Amount:</strong> Ksh {{ number_format($SavingsTarget->target_amount, 2) }}</p>
                        <p><strong>Description:</strong> {{ $SavingsTarget->description }}</p>
                        <p><strong>Target Date:</strong> {{ $SavingsTarget->target_date->format('F d, Y') }}</p>
                        <p><strong>Status:</strong> {{ $SavingsTarget->is_achieved ? 'Achieved' : 'In Progress' }}</p>
                    </div>

                    <h3 class="text-xl font-semibold mb-4">Savings Payments</h3>
                    <div class="mb-4 p-4 rounded-lg {{ $savingsPayments->sum('amount') >= $SavingsTarget->target_amount ? 'bg-green-100' : 'bg-blue-100' }}">
                        <p class="text-lg font-bold">Total Payments: Ksh
                            {{ number_format($savingsPayments->sum('amount'), 2) }}</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="{{ $savingsPayments->sum('amount') >= $SavingsTarget->target_amount ? 'bg-green-600' : 'bg-blue-600' }} h-2.5 rounded-full"
                                    style="width: {{ min(($savingsPayments->sum('amount') / $SavingsTarget->target_amount) * 100, 100) }}%">
                                </div>
                            </div>
                            <p class="text-sm mt-1">
                                {{ number_format(min(($savingsPayments->sum('amount') / $SavingsTarget->target_amount) * 100, 100), 2) }}%
                                {{ $savingsPayments->sum('amount') >= $SavingsTarget->target_amount ? 'Target reached! 🎉🚀💪💰' : 'to victory 🎉🚀💪💰' }}</p>
                        </div>
                        <p class="text-sm mt-2">
                            @php
                                $remainingDays = max(0, now()->diffInDays($SavingsTarget->target_date, false));
                                $remainingWeeks = floor($remainingDays / 7);
                                $remainingMonths = floor($remainingDays / 30);
                            @endphp
                            @if ($savingsPayments->sum('amount') >= $SavingsTarget->target_amount)
                                Target achieved!
                            @elseif ($remainingDays > 0)

                                {{ floor($remainingDays) }} day{{ floor($remainingDays) != 1 ? 's' : '' }} remaining
                                @if ($remainingWeeks > 0)
                                    ({{ $remainingWeeks }} week{{ $remainingWeeks != 1 ? 's' : '' }})
                                @endif
                                @if ($remainingMonths > 0)
                                    ({{ $remainingMonths }} month{{ $remainingMonths != 1 ? 's' : '' }})
                                @endif
                            @else
                                Target date reached
                            @endif
                        </p>
                        <p class="text-sm mt-2 italic">
                            @php
                                $encouragements = [
                                    "You're doing great!",
                                    'Keep up the good work!',
                                    "You're on your way to success!",
                                    'Every penny counts!',
                                    "You're making progress!",
                                    'Stay motivated!',
                                    "You've got this!",
                                    'Your future self will thank you!',
                                    'Small steps lead to big results!',
                                    'Believe in yourself!',
                                    "You're crushing it!",
                                    'Keep pushing forward!',
                                    'Your dedication is inspiring!',
                                    "You're building a brighter future!",
                                    'Success is within reach!',
                                    'Your efforts will pay off!',
                                    "You're a savings superstar!",
                                    'Keep your eyes on the prize!',
                                    "You're making your dreams a reality!",
                                    'Your commitment is admirable!',
                                ];
                                echo $savingsPayments->sum('amount') >= $SavingsTarget->target_amount ? 'Congratulations! You\'ve reached your target!' : $encouragements[array_rand($encouragements)];
                            @endphp
                        </p>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('savings.save', ['SavingsTarget' => $SavingsTarget]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b">Payment Date</th>
                                    <th class="py-2 px-4 border-b">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($savingsPayments as $payment)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $payment->payment_date->format('F d, Y') }}
                                        </td>
                                        <td class="py-2 px-4 border-b">Ksh {{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
