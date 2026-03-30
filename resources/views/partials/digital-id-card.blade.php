@php
    /** @var \App\Models\User $user */
    $birthdate = $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('F d, Y') : 'N/A';
    $photoUrl = $user->profile_photo_url;
    $defaultPhotoUrl = asset('images/city_of_general_trias_seal.png');
    $issuedAt = optional($onlineId)->issued_at ? optional($onlineId)->issued_at->format('M d, Y') : 'N/A';
    $idNumber = optional($onlineId)->id_number ?? 'N/A';
    $qrAccountNumber = trim((string) ($user->account_number ?? ''));
    $qrCodeDataUri = null;

    if ($qrAccountNumber !== '') {
        try {
            $qrOptions = new \chillerlan\QRCode\QROptions([
                'outputType' => \chillerlan\QRCode\Output\QROutputInterface::MARKUP_SVG,
                'outputBase64' => true,
                'eccLevel' => \chillerlan\QRCode\Common\EccLevel::M,
                'scale' => 4,
            ]);

            // QR content is based strictly on resident account number.
            $qrCodeDataUri = (new \chillerlan\QRCode\QRCode($qrOptions))->render($qrAccountNumber);
        } catch (\Throwable $e) {
            $qrCodeDataUri = null;
        }
    }
@endphp

<style>
    .digital-id-shell {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .digital-id-card {
        position: relative;
        background: linear-gradient(155deg, #fdfefe 0%, #eef5ff 100%);
        border: 1px solid #c4d4ea;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 22px 50px rgba(12, 36, 74, 0.18);
    }

    .digital-id-card::before {
        content: '';
        position: absolute;
        top: -120px;
        right: -120px;
        width: 340px;
        height: 340px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(45, 120, 190, 0.2), transparent 68%);
        pointer-events: none;
    }

    .digital-id-card::after {
        content: '';
        position: absolute;
        bottom: -140px;
        left: -80px;
        width: 360px;
        height: 280px;
        background: radial-gradient(circle, rgba(14, 68, 132, 0.15), transparent 70%);
        pointer-events: none;
    }

    .digital-id-header {
        position: relative;
        z-index: 2;
        padding: 16px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        background: linear-gradient(115deg, #0f2e6d 0%, #1b5ca5 56%, #3f8ccc 100%);
        color: #ffffff;
        border-bottom: 2px solid rgba(255, 255, 255, 0.35);
    }

    .digital-id-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .digital-id-seal {
        width: 52px;
        height: 52px;
        border-radius: 999px;
        border: 2px solid rgba(255, 255, 255, 0.65);
        object-fit: cover;
        background: rgba(255, 255, 255, 0.1);
    }

    .digital-id-header p {
        margin: 0;
        line-height: 1.15;
    }

    .digital-id-meta-small {
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .digital-id-meta-main {
        font-size: 19px;
        font-weight: 800;
        letter-spacing: 0.45px;
        margin-top: 3px;
    }

    .digital-id-badge {
        padding: 8px 13px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 9px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.9px;
        text-transform: uppercase;
        white-space: nowrap;
        text-align: right;
        line-height: 1.35;
        background: rgba(255, 255, 255, 0.12);
    }

    .digital-id-body {
        position: relative;
        z-index: 2;
        padding: 20px 22px 18px;
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 18px;
    }

    .digital-id-photo-panel {
        background: linear-gradient(180deg, #f6fbff 0%, #e7f0fb 100%);
        border: 1px solid #bfd3ea;
        border-radius: 14px;
        padding: 10px;
    }

    .digital-id-photo-box {
        border: 2px solid #2a5d9d;
        border-radius: 10px;
        height: 204px;
        overflow: hidden;
        background: #eef4fe;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .digital-id-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .digital-id-photo-box img.digital-id-photo-placeholder {
        width: 82%;
        height: 82%;
        object-fit: contain;
        object-position: center;
        padding: 6px;
    }

    .digital-id-photo-fallback {
        color: #355f98;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }

    .digital-id-photo-label {
        margin-top: 8px;
        text-align: center;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #2a4e7a;
    }

    .digital-id-name {
        font-size: 24px;
        font-weight: 800;
        color: #172035;
        margin: 0 0 12px;
        border-bottom: 1px solid #cddaf0;
        padding-bottom: 10px;
    }

    .digital-id-info {
        min-width: 0;
    }

    .digital-id-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px 14px;
    }

    .digital-id-field {
        min-width: 0;
    }

    .digital-id-field.full {
        grid-column: 1 / -1;
    }

    .digital-id-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #205595;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        margin-bottom: 3px;
    }

    .digital-id-value {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1d2433;
        line-height: 1.3;
        word-break: break-word;
    }

    .digital-id-value.mono {
        font-family: Consolas, 'Courier New', monospace;
        letter-spacing: 0.25px;
        overflow-wrap: anywhere;
    }

    .digital-id-bottom-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 170px;
        gap: 10px;
        align-items: end;
    }

    .digital-id-signature {
        border-top: 1px dashed #7d8ba3;
        padding-top: 6px;
        font-size: 10px;
        color: #53627e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .digital-id-qr {
        justify-self: end;
        width: 160px;
        background: #ffffff;
        border: 1px solid #bac8de;
        border-radius: 10px;
        padding: 6px;
        box-shadow: inset 0 0 0 1px #e4ecf8;
    }

    .digital-id-qr img {
        display: block;
        width: 100%;
        height: auto;
    }

    .digital-id-qr-empty {
        min-height: 148px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 11px;
        color: #5f6f8f;
        padding: 8px;
        border: 1px dashed #c8d5ea;
        border-radius: 6px;
        background: #f8fbff;
    }

    .digital-id-qr-label {
        margin-top: 5px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.65px;
        color: #304a74;
        text-align: center;
        text-transform: uppercase;
    }

    .digital-id-footer {
        position: relative;
        z-index: 2;
        padding: 11px 22px;
        border-top: 1px solid #cad9ee;
        background: linear-gradient(180deg, #f4f8ff 0%, #e9f0fb 100%);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        font-size: 11px;
        color: #354866;
        font-weight: 600;
    }

    .digital-id-footer strong {
        color: #132f5c;
    }

    @media (max-width: 760px) {
        .digital-id-card {
            border-radius: 14px;
        }

        .digital-id-header {
            gap: 8px;
            padding: 12px 14px;
        }

        .digital-id-badge {
            padding: 7px 10px;
            font-size: 9px;
        }

        .digital-id-meta-main { font-size: 14px; }
        .digital-id-meta-small { font-size: 9px; }

        .digital-id-body {
            grid-template-columns: 120px minmax(0, 1fr);
            gap: 10px;
            padding: 12px 14px;
        }

        .digital-id-photo-panel {
            padding: 7px;
        }

        .digital-id-photo-box {
            height: 130px;
            border-width: 1px;
        }

        .digital-id-photo-label {
            margin-top: 5px;
            font-size: 8px;
            letter-spacing: 0.55px;
        }

        .digital-id-name {
            font-size: 15px;
            margin: 0 0 8px;
            padding-bottom: 7px;
        }

        .digital-id-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 6px 8px;
        }

        .digital-id-label {
            font-size: 8px;
            margin-bottom: 2px;
            letter-spacing: 0.45px;
        }

        .digital-id-value {
            font-size: 10px;
            line-height: 1.25;
        }

        .digital-id-bottom-row {
            grid-template-columns: minmax(0, 1fr) 104px;
            gap: 8px;
            margin-top: 6px;
        }

        .digital-id-signature {
            font-size: 8px;
            padding-top: 5px;
            letter-spacing: 0.4px;
        }

        .digital-id-qr {
            width: 100%;
            padding: 4px;
        }

        .digital-id-qr-empty {
            min-height: 88px;
            font-size: 9px;
            padding: 6px;
        }

        .digital-id-qr-label {
            margin-top: 4px;
            font-size: 8px;
            letter-spacing: 0.45px;
        }

        .digital-id-footer {
            padding: 8px 14px;
            gap: 8px;
            font-size: 9px;
        }
    }

    @media (max-width: 480px) {
        .digital-id-header {
            padding: 10px 12px;
        }

        .digital-id-header-left { gap: 6px; }

        .digital-id-seal { width: 34px; height: 34px; }

        .digital-id-meta-main { font-size: 12px; }
        .digital-id-meta-small { font-size: 8px; }

        .digital-id-badge {
            font-size: 8px;
            padding: 6px 8px;
        }

        .digital-id-body {
            grid-template-columns: 104px minmax(0, 1fr);
            padding: 10px 12px;
            gap: 8px;
        }

        .digital-id-photo-box {
            height: 112px;
        }

        .digital-id-grid {
            gap: 5px 7px;
        }

        .digital-id-label { font-size: 7px; }
        .digital-id-value { font-size: 9px; }

        .digital-id-bottom-row {
            grid-template-columns: minmax(0, 1fr) 92px;
            gap: 6px;
        }

        .digital-id-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            font-size: 8px;
            padding: 7px 12px;
        }
    }

    @media (max-width: 390px) {
        .digital-id-header {
            padding: 9px 10px;
        }

        .digital-id-body {
            grid-template-columns: 96px minmax(0, 1fr);
            padding: 9px 10px;
            gap: 7px;
        }

        .digital-id-photo-box {
            height: 104px;
        }

        .digital-id-name {
            font-size: 14px;
            margin-bottom: 6px;
            padding-bottom: 6px;
        }

        .digital-id-label { font-size: 6.8px; }
        .digital-id-value { font-size: 8.6px; }

        .digital-id-bottom-row {
            grid-template-columns: minmax(0, 1fr) 84px;
            gap: 5px;
        }

        .digital-id-footer {
            padding: 6px 10px;
            font-size: 7.8px;
        }
    }

    @media (max-width: 360px) {
        .digital-id-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .digital-id-badge {
            align-self: flex-start;
        }

        .digital-id-body {
            grid-template-columns: 1fr;
            padding: 9px;
            gap: 7px;
        }

        .digital-id-photo-panel {
            max-width: 96px;
            margin: 0;
        }

        .digital-id-photo-box {
            height: 100px;
        }

        .digital-id-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 4px 6px;
        }

        .digital-id-bottom-row {
            grid-template-columns: minmax(0, 1fr) 80px;
        }
    }

    @media (max-width: 430px) and (orientation: portrait) {
        .digital-id-shell {
            max-width: 390px;
        }

        .digital-id-card {
            border-radius: 12px;
        }
    }

    @media (max-height: 500px) and (orientation: landscape) {
        .digital-id-shell {
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .digital-id-card {
            min-width: 560px;
        }
    }
</style>

<div class="digital-id-shell">
    <div class="digital-id-card">
        <div class="digital-id-header">
            <div class="digital-id-header-left">
                <img src="{{ asset('images/city_of_general_trias_seal.png') }}" alt="Barangay Seal" class="digital-id-seal">
                <div>
                    <!-- <p class="digital-id-meta-small">Republic of the Philippines</p> -->
                    <p class="digital-id-meta-main">Barangay Digital ID</p>
                    <p class="digital-id-meta-small">SAN JUAN I</p>
                </div>
            </div>
            <div class="digital-id-badge">
                Barangay ID<br>
                {{ $idNumber }}
            </div>
        </div>

        <div class="digital-id-body">
            <div class="digital-id-photo-panel">
                <div class="digital-id-photo-box">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Profile Photo">
                    @else
                        <img src="{{ $defaultPhotoUrl }}" alt="Default resident photo template" class="digital-id-photo-placeholder">
                    @endif
                </div>
                <div class="digital-id-photo-label">Resident Photo</div>
            </div>

            <div class="digital-id-info">
                <h3 class="digital-id-name">{{ $user->getFullName() }}</h3>

                <div class="digital-id-grid">
                    <div class="digital-id-field">
                        <span class="digital-id-label">ID Number</span>
                        <span class="digital-id-value mono">{{ $idNumber }}</span>
                    </div>

                    <div class="digital-id-field">
                        <span class="digital-id-label">Account Number</span>
                        <span class="digital-id-value mono">{{ $user->account_number ?? 'N/A' }}</span>
                    </div>

                    <div class="digital-id-field">
                        <span class="digital-id-label">Birthdate</span>
                        <span class="digital-id-value">{{ $birthdate }}</span>
                    </div>
                    <div class="digital-id-field">
                        <span class="digital-id-label">Gender</span>
                        <span class="digital-id-value">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</span>
                    </div>
                    <div class="digital-id-field">
                        <span class="digital-id-label">Barangay</span>
                        <span class="digital-id-value">{{ $user->barangay ?? 'N/A' }}</span>
                    </div>

                    <div class="digital-id-field">
                        <span class="digital-id-label">Municipality/City</span>
                        <span class="digital-id-value">{{ $user->municipality_city ?? 'N/A' }}</span>
                    </div>

                    <div class="digital-id-field">
                        <span class="digital-id-label">Issued Date</span>
                        <span class="digital-id-value">{{ $issuedAt }}</span>
                    </div>
                </div>

                <div class="digital-id-bottom-row">
                    <div class="digital-id-signature">Resident's Signature</div>
                    <div class="digital-id-qr">
                        @if($qrCodeDataUri)
                            <img src="{{ $qrCodeDataUri }}" alt="QR code for account number {{ $qrAccountNumber }}">
                        @else
                            <div class="digital-id-qr-empty">No account number available for QR.</div>
                        @endif
                        <div class="digital-id-qr-label">Scan to verify ID</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="digital-id-footer">
            <!-- <span><strong>Valid Until: </strong> [insert date]</span> -->
             <span> </span>
             <!-- <span><strong>VALID UNTIL: </strong> {{ $issuedAt }}</span> -->
            <span><strong>VALID UNTIL: </strong> N/ A</span>
        </div>
    </div>
</div>
