@extends('layouts.app')

@section('title', 'Financial Tools & EMI Calculator | Kedia Builders & Colonizers')

@section('content')

<section class="py-16 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Financial Engineering Suite') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold text-[#0E1520] tracking-tight">
                {{ __('Home Loan EMI & Stamp Duty Calculator') }}
            </h1>
            <p class="text-base text-[#0E1520]/70 leading-relaxed">
                {{ __('Plan your property investment with precision. Compute monthly installments, total interest, and statutory Rajasthan government stamp duty with live sliders.') }}
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-6 sm:px-10 lg:px-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left 7 Cols: Sliders & Controls -->
            <div class="lg:col-span-7 bg-[#FAF8F5] p-6 sm:p-8 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-6">
                <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Mortgage Parameters') }}</h3>

                <!-- Property Value (basis for stamp duty & registration) -->
                <div class="space-y-2">
                    <div class="flex justify-between font-mono text-xs">
                        <label for="inputProperty" class="text-[#0E1520]/70 uppercase">{{ __('Property Value (INR)') }}</label>
                        <span class="font-bold text-sm text-[#0E1520]" id="displayProperty">₹ 50,00,000</span>
                    </div>
                    <input type="range" id="inputProperty" min="1000000" max="30000000" step="100000" value="5000000" oninput="calculateEmi()" class="w-full accent-[#0E1520]">
                    <div class="flex justify-between font-mono text-[10px] text-[#0E1520]/40">
                        <span>₹ 10 L</span>
                        <span>{{ __('₹ 1.5 Cr') }}</span>
                        <span>{{ __('₹ 3 Cr') }}</span>
                    </div>
                </div>

                <!-- Loan Amount -->
                <div class="space-y-2">
                    <div class="flex justify-between font-mono text-xs">
                        <span class="text-[#0E1520]/70 uppercase">{{ __('Loan Amount (INR)') }}</span>
                        <span class="font-bold text-sm text-[#0E1520]" id="displayLoan">₹ 40,00,000</span>
                    </div>
                    <input type="range" id="inputLoan" min="1000000" max="20000000" step="100000" value="4000000" oninput="calculateEmi()" class="w-full accent-[#0E1520]">
                    <div class="flex justify-between font-mono text-[10px] text-[#0E1520]/40">
                        <span>₹ 10 L</span>
                        <span>{{ __('₹ 1 Cr') }}</span>
                        <span>{{ __('₹ 2 Cr') }}</span>
                    </div>
                </div>

                <!-- Interest Rate -->
                <div class="space-y-2">
                    <div class="flex justify-between font-mono text-xs">
                        <span class="text-[#0E1520]/70 uppercase">{{ __('Interest Rate (% p.a.)') }}</span>
                        <span class="font-bold text-sm text-[#0E1520]" id="displayRate">8.5 %</span>
                    </div>
                    <input type="range" id="inputRate" min="6.5" max="14.0" step="0.1" value="8.5" oninput="calculateEmi()" class="w-full accent-[#0E1520]">
                    <div class="flex justify-between font-mono text-[10px] text-[#0E1520]/40">
                        <span>6.5 %</span>
                        <span>9.0 %</span>
                        <span>14.0 %</span>
                    </div>
                </div>

                <!-- Tenure Years -->
                <div class="space-y-2">
                    <div class="flex justify-between font-mono text-xs">
                        <span class="text-[#0E1520]/70 uppercase">{{ __('Tenure (Years)') }}</span>
                        <span class="font-bold text-sm text-[#0E1520]" id="displayTenure">{{ __('20 Years') }}</span>
                    </div>
                    <input type="range" id="inputTenure" min="5" max="30" step="1" value="20" oninput="calculateEmi()" class="w-full accent-[#0E1520]">
                    <div class="flex justify-between font-mono text-[10px] text-[#0E1520]/40">
                        <span>{{ __('5 Years') }}</span>
                        <span>{{ __('15 Years') }}</span>
                        <span>{{ __('30 Years') }}</span>
                    </div>
                </div>

                <!-- Rajasthan Bank Partners -->
                <div class="pt-4 border-t border-[#0E1520]/10">
                    <div class="text-[10px] font-mono text-[#0E1520]/60 uppercase mb-2">{{ __('Approved Banking Partners') }}</div>
                    <div class="flex flex-wrap gap-2 font-mono text-xs font-semibold">
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#0E1520]/10">{{ __('SBI') }}</span>
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#0E1520]/10">{{ __('HDFC Bank') }}</span>
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#0E1520]/10">{{ __('ICICI Bank') }}</span>
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#0E1520]/10">{{ __('Axis Bank') }}</span>
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#0E1520]/10">{{ __('PNB Housing') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Computed Output Box -->
            <div class="lg:col-span-5 bg-[#0E1520] text-white p-6 sm:p-8 rounded-3xl border border-white/10 shadow-2xl flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Estimated Repayment') }}</span>
                    
                    <div class="mt-4 pb-6 border-b border-white/10">
                        <div class="text-xs font-mono text-white/50 uppercase">{{ __('Monthly EMI Payment') }}</div>
                        <div class="font-serif text-3xl sm:text-4xl font-bold text-white mt-1" id="resultEmi">₹ 34,713</div>
                    </div>

                    <div class="py-4 space-y-3 font-mono text-xs border-b border-white/10">
                        <div class="flex justify-between text-white/70">
                            <span>{{ __('Principal Loan:') }}</span>
                            <span class="font-bold text-white" id="resultPrincipal">₹ 40,00,000</span>
                        </div>
                        <div class="flex justify-between text-white/70">
                            <span>{{ __('Total Interest:') }}</span>
                            <span class="font-bold text-[#D4E845]" id="resultInterest">₹ 43,31,117</span>
                        </div>
                        <div class="flex justify-between text-white/70">
                            <span>{{ __('Total Amount Payable:') }}</span>
                            <span class="font-bold text-white" id="resultTotal">₹ 83,31,117</span>
                        </div>
                    </div>

                    <div class="pt-4 font-mono text-[11px] text-white/60 space-y-1">
                        <div>{{ __('Rajasthan Stamp Duty (Est. 5% of property value):') }} <span class="font-bold text-white" id="resultStamp">₹ 2,50,000</span></div>
                        <div>{{ __('Registration Fee (Est. 1% of property value):') }} <span class="font-bold text-white" id="resultReg">₹ 50,000</span></div>
                        <div class="text-white/40 pt-1">{{ __('Indicative only — rates vary by buyer category and DLC rate; confirm with the sub-registrar.') }}</div>
                    </div>
                </div>

                <button type="button" onclick="openSiteVisitModal()" class="w-full py-3.5 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-xs uppercase tracking-wider transition-all shadow-lg text-center">
                    {{ __('Apply For Pre-Approved Bank Loan →') }}
                </button>
            </div>

        </div>

    </div>
</section>

<script>
    function calculateEmi() {
        const propertyValue = parseFloat(document.getElementById('inputProperty').value);
        const loanInput = document.getElementById('inputLoan');
        // A loan can't exceed the property's value.
        if (parseFloat(loanInput.value) > propertyValue) loanInput.value = propertyValue;
        const P = parseFloat(loanInput.value);
        document.getElementById('displayProperty').innerText = '₹ ' + propertyValue.toLocaleString('en-IN');
        const annualRate = parseFloat(document.getElementById('inputRate').value);
        const tenureYears = parseInt(document.getElementById('inputTenure').value);

        document.getElementById('displayLoan').innerText = '₹ ' + P.toLocaleString('en-IN');
        document.getElementById('displayRate').innerText = annualRate.toFixed(1) + ' %';
        document.getElementById('displayTenure').innerText = tenureYears + ' Years';

        const r = (annualRate / 12) / 100;
        const n = tenureYears * 12;

        const emi = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        const totalAmount = emi * n;
        const totalInterest = totalAmount - P;

        document.getElementById('resultEmi').innerText = '₹ ' + Math.round(emi).toLocaleString('en-IN');
        document.getElementById('resultPrincipal').innerText = '₹ ' + P.toLocaleString('en-IN');
        document.getElementById('resultInterest').innerText = '₹ ' + Math.round(totalInterest).toLocaleString('en-IN');
        document.getElementById('resultTotal').innerText = '₹ ' + Math.round(totalAmount).toLocaleString('en-IN');

        // Stamp duty and registration are charged on the property value, not the loan.
        document.getElementById('resultStamp').innerText = '₹ ' + Math.round(propertyValue * 0.05).toLocaleString('en-IN');
        document.getElementById('resultReg').innerText = '₹ ' + Math.round(propertyValue * 0.01).toLocaleString('en-IN');
    }

    document.addEventListener('DOMContentLoaded', calculateEmi);
</script>

@endsection
