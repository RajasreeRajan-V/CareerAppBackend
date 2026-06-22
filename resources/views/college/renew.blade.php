{{-- resources/views/college/renew.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Subscription Renewal — {{ config('app.name', 'CareerApp') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal:        #306060;
            --teal-dark:   #1e4242;
            --teal-mid:    #254f4f;
            --teal-light:  #4a9090;
            --teal-pale:   #e8f2f2;
            --amber:       #e8a045;
            --amber-dark:  #c8832a;
            --amber-light: #fdf3e3;
            --white:       #ffffff;
            --text-dark:   #1a2e2e;
            --text-mid:    #4a6060;
            --text-light:  #7a9090;
            --border:      #c8dede;
            --danger:      #c0392b;
            --danger-bg:   #fdf0ee;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(145deg, #1e4242 0%, #306060 45%, #254f4f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.06);
            pointer-events: none;
        }
        body::before { width: 700px; height: 700px; top: -200px; right: -200px; }
        body::after  { width: 500px; height: 500px; bottom: -150px; left: -150px; }

        /* ── CARD ── */
        .card {
            background: var(--white);
            width: 100%;
            max-width: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 32px 64px rgba(0,0,0,0.28),
                0 8px 24px rgba(0,0,0,0.14);
            animation: slideUp 0.55s cubic-bezier(0.34, 1.46, 0.64, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }

        /* ── HEADER ── */
        .card-header {
            background: linear-gradient(135deg, var(--teal-dark) 0%, var(--teal) 100%);
            padding: 32px 36px 28px;
            position: relative;
            overflow: hidden;
        }
        .card-header::after {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,0.05);
            top: -80px; right: -80px;
            pointer-events: none;
        }

        .header-icon-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .header-logo {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.12);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.18);
            flex-shrink: 0;
        }

        .expired-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(232,160,69,0.18);
            border: 1px solid rgba(232,160,69,0.45);
            color: var(--amber);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .card-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: var(--white);
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 13.5px;
            color: rgba(255,255,255,0.65);
            line-height: 1.5;
        }

        /* ── EXPIRY STRIP ── */
        .expiry-strip {
            background: var(--amber-light);
            border-top: 1px solid #f0d8b0;
            border-bottom: 1px solid #f0d8b0;
            padding: 14px 36px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #7a4f10;
        }
        .expiry-strip i { font-size: 16px; color: var(--amber-dark); flex-shrink: 0; }
        .expiry-strip strong { font-weight: 600; }

        /* ── BODY ── */
        .card-body { padding: 32px 36px 36px; }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }
        .alert i { flex-shrink: 0; margin-top: 1px; }
        .alert-warning { background: var(--amber-light); border: 1px solid #f0d8b0; color: #7a4f10; }
        .alert-warning i { color: var(--amber-dark); }
        .alert-danger   { background: var(--danger-bg); border: 1px solid #f5c6c0; color: var(--danger); }
        .alert-success  { background: #edf7f0; border: 1px solid #a8d8b8; color: #1e6636; }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 14px;
        }

        /* ── ACCOUNT DETAILS GRID ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 26px;
        }
        .meta-cell {
            background: #f4fafa;
            border: 1.5px solid #dceaea;
            border-radius: 10px;
            padding: 12px 14px;
        }
        .meta-cell .mc-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 4px;
        }
        .meta-cell .mc-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }
        .mc-value.expired {
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── PLAN CARDS ── */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 10px;
            margin-bottom: 26px;
        }

        .plan-card {
            border: 2px solid var(--border);
            border-radius: 13px;
            padding: 16px 12px;
            cursor: pointer;
            position: relative;
            text-align: center;
            background: var(--white);
            transition: border-color .2s, box-shadow .2s, transform .15s;
        }
        .plan-card:hover {
            border-color: var(--teal-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(48,96,96,.12);
        }
        .plan-card.selected {
            border-color: var(--teal);
            background: var(--teal-pale);
            box-shadow: 0 0 0 3px rgba(48,96,96,.1);
        }
        .plan-card input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .plan-badge {
            position: absolute;
            top: -1px; right: -1px;
            background: var(--teal);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .04em;
            padding: 3px 8px;
            border-radius: 0 11px 0 8px;
            text-transform: uppercase;
        }
        .plan-icon {
            width: 36px; height: 36px;
            background: var(--teal-pale);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: .9rem;
            color: var(--teal);
        }
        .plan-card.selected .plan-icon { background: rgba(48,96,96,.15); }
        .pc-name  { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px; }
        .pc-price { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--teal); line-height: 1; }
        .pc-price span { font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 400; color: var(--text-light); }
        .pc-desc  { font-size: 11px; color: var(--text-light); margin-top: 5px; }

        /* ── DIVIDER ── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 22px 0;
        }

        /* ── FORM ── */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        .form-group { margin-bottom: 12px; }
        .form-group:last-of-type { margin-bottom: 0; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            background: #fafefe;
            transition: border-color .22s, box-shadow .22s;
            outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(48,96,96,.12);
            background: var(--white);
        }
        input[readonly] {
            background: #f0f5f5;
            color: var(--text-mid);
            cursor: not-allowed;
        }
        textarea { resize: vertical; min-height: 80px; }

        /* ── SUMMARY BAR ── */
        .summary-bar {
            background: var(--teal-pale);
            border: 1.5px solid rgba(48,96,96,.2);
            border-radius: 11px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 0;
            margin-top: 18px;
        }
        .sb-left .sb-label  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--teal-mid); margin-bottom: 2px; }
        .sb-left .sb-period { font-size: 12px; color: var(--text-light); }
        .sb-amount { font-family: 'DM Serif Display', serif; font-size: 22px; color: var(--teal); }

        /* ── SUBMIT ── */
        .btn-renew {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-top: 22px;
            transition: transform .18s, box-shadow .18s;
            box-shadow: 0 4px 16px rgba(48,96,96,.3);
            position: relative;
            overflow: hidden;
        }
        .btn-renew::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.1), transparent);
            opacity: 0;
            transition: opacity .2s;
        }
        .btn-renew:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(48,96,96,.38); }
        .btn-renew:hover::before { opacity: 1; }
        .btn-renew:active { transform: translateY(0); }

        /* ── BACK LINK ── */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 18px;
            font-size: 13px;
            color: var(--text-light);
            text-decoration: none;
            transition: color .2s;
        }
        .back-link:hover { color: var(--teal); }

        /* ── INFO ROW ── */
        .info-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-light);
            justify-content: center;
            margin-top: 14px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 540px) {
            .card-header, .card-body { padding-left: 22px; padding-right: 22px; }
            .expiry-strip             { padding-left: 22px; padding-right: 22px; }
            .form-row                 { grid-template-columns: 1fr; }
            .meta-grid                { grid-template-columns: 1fr; }
            .plan-grid                { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="card">

    {{-- ══ HEADER ══ --}}
    <div class="card-header">
        <div class="header-icon-row">
            <div class="header-logo">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <span class="expired-badge">
                <i class="fa-solid fa-circle-exclamation"></i> Subscription Expired
            </span>
        </div>
        <h1>Renew Your Subscription</h1>
        <p>
            Your annual access has ended. Renew now to restore full dashboard access for
            <strong style="color:rgba(255,255,255,0.9)">{{ $college->name }}</strong>.
        </p>
    </div>

    {{-- ══ EXPIRY STRIP ══ --}}
    <div class="expiry-strip">
        <i class="fa-regular fa-calendar-xmark"></i>
        <span>
            Expired on
            <strong>{{ \Carbon\Carbon::parse($college->created_at)->addYear()->format('d M Y') }}</strong>
            &mdash; {{ \Carbon\Carbon::parse($college->created_at)->addYear()->diffForHumans() }}
        </span>
    </div>

    {{-- ══ BODY ══ --}}
    <div class="card-body">

        {{-- Alerts --}}
        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── ACCOUNT DETAILS ── --}}
        <p class="section-label">Account Details</p>
        <div class="meta-grid">
            <div class="meta-cell">
                <div class="mc-label">College Name</div>
                <div class="mc-value">{{ $college->name }}</div>
            </div>
            <div class="meta-cell">
                <div class="mc-label">Registered Email</div>
                <div class="mc-value">{{ $college->email }}</div>
            </div>
            <div class="meta-cell">
                <div class="mc-label">Member Since</div>
                <div class="mc-value">{{ \Carbon\Carbon::parse($college->created_at)->format('d M Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="mc-label">Expired On</div>
                <div class="mc-value expired">
                    <i class="fa-solid fa-circle-xmark" style="font-size:.8rem;"></i>
                    {{ \Carbon\Carbon::parse($college->created_at)->addYear()->format('d M Y') }}
                </div>
            </div>
        </div>

        {{-- ── FORM ── --}}
        <form method="POST"
              action="{{ route('college.subscription.renew.submit', ['college' => $college->id]) }}"
              id="renewForm">
            @csrf

            {{-- ── PLAN CARDS ── --}}
            <p class="section-label">Select a Plan</p>
            <div class="plan-grid" id="planGrid">

                <label class="plan-card {{ old('plan', '6months') === '6months' ? 'selected' : '' }}"
                       for="plan_6m">
                    <input type="radio" name="plan" id="plan_6m" value="6months"
                           {{ old('plan', '6months') === '6months' ? 'checked' : '' }}>
                    <div class="plan-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="pc-name">6 Months</div>
                    <div class="pc-price">₹2,499<span>/6mo</span></div>
                    <div class="pc-desc">Short-term access</div>
                </label>

                <label class="plan-card {{ old('plan') === '1year' ? 'selected' : '' }}"
                       for="plan_1y">
                    <div class="plan-badge">Popular</div>
                    <input type="radio" name="plan" id="plan_1y" value="1year"
                           {{ old('plan') === '1year' ? 'checked' : '' }}>
                    <div class="plan-icon"><i class="fa-solid fa-star"></i></div>
                    <div class="pc-name">1 Year</div>
                    <div class="pc-price">₹3,999<span>/yr</span></div>
                    <div class="pc-desc">Most popular plan</div>
                </label>

                <label class="plan-card {{ old('plan') === '2years' ? 'selected' : '' }}"
                       for="plan_2y">
                    <input type="radio" name="plan" id="plan_2y" value="2years"
                           {{ old('plan') === '2years' ? 'checked' : '' }}>
                    <div class="plan-icon"><i class="fa-solid fa-crown"></i></div>
                    <div class="pc-name">2 Years</div>
                    <div class="pc-price">₹6,999<span>/2yr</span></div>
                    <div class="pc-desc">Best value</div>
                </label>

            </div>

            <div class="divider"></div>

            {{-- ── CONTACT PERSON ── --}}
            <p class="section-label">Contact Person for Renewal</p>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_name">
                        Contact Name <span style="color:var(--danger)">*</span>
                    </label>
                    <input type="text" id="contact_name" name="contact_name"
                           placeholder="Dr. / Mr. / Ms."
                           value="{{ old('contact_name') }}" required>
                    @error('contact_name')
                        <span style="color:var(--danger);font-size:11px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_phone">
                        Contact Phone <span style="color:var(--danger)">*</span>
                    </label>
                    <input type="tel" 
                       id="contact_phone" 
                       name="contact_phone"
                       placeholder="+91 98765 43210"
                       value="{{ old('contact_phone') }}"
                       pattern="[0-9]{10}"
                       maxlength="10"
                       minlength="10"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                       required>
                
                @error('contact_phone')
                    <span style="color:var(--danger);font-size:11px;">{{ $message }}</span>
                @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="notes">
                    Additional Notes
                    <span style="color:var(--text-light);font-weight:400">(optional)</span>
                </label>
                <textarea id="notes" name="notes"
                          placeholder="Any special requests or queries…">{{ old('notes') }}</textarea>
            </div>

            {{-- hidden college id --}}
            <input type="hidden" name="college_id" value="{{ $college->id }}">

            {{-- ── LIVE SUMMARY ── --}}
            <div class="summary-bar">
                <div class="sb-left">
                    <div class="sb-label">Selected Plan</div>
                    <div class="sb-period" id="sumPeriod">
                        6 Months &mdash; valid till
                        {{ \Carbon\Carbon::now()->addMonths(6)->format('d M Y') }}
                    </div>
                </div>
                <div class="sb-amount" id="sumAmount">₹2,499</div>
            </div>

            <button type="submit" class="btn-renew">
                <i class="fa-solid fa-arrow-rotate-right"></i>
                Submit Renewal Request
            </button>

        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>

        <div class="info-row">
            <i class="fa-solid fa-shield-halved" style="color:var(--teal-light)"></i>
            Requests are reviewed by the admin within 1–2 business days.
        </div>

    </div>{{-- /card-body --}}
</div>

<script>
    const plans = {
        '6months': { label: '6 Months', price: '₹2,499', months: 6  },
        '1year':   { label: '1 Year',   price: '₹3,999', months: 12 },
        '2years':  { label: '2 Years',  price: '₹6,999', months: 24 },
    };

    function addMonths(n) {
        const d = new Date();
        d.setMonth(d.getMonth() + n);
        return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function updateSummary(value) {
        const p = plans[value];
        if (!p) return;
        document.getElementById('sumAmount').textContent = p.price;
        document.getElementById('sumPeriod').textContent =
            p.label + ' \u2014 valid till ' + addMonths(p.months);
    }

    // init on load
    const init = document.querySelector('input[name="plan"]:checked');
    if (init) updateSummary(init.value);

    document.getElementById('planGrid').addEventListener('change', function (e) {
        if (e.target.name !== 'plan') return;
        document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
        e.target.closest('.plan-card').classList.add('selected');
        updateSummary(e.target.value);
    });
</script>

</body>
</html>