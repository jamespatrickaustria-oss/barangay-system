@php
    /** @var \App\Models\User $user */
    $showInlineFlipControls = $showInlineFlipControls ?? true;

    $fullName = method_exists($user, 'getFullName') ? $user->getFullName() : trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
    $fullName = $fullName !== '' ? $fullName : 'N/A';

    $idNumber = optional($onlineId)->id_number ?? 'N/A';
    $accountNumber = $user->account_number ?? 'N/A';
    $birthdate = $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('F d, Y') : 'N/A';
    $gender = $user->gender ? ucfirst($user->gender) : 'N/A';
    $barangay = $user->barangay ?? 'N/A';
    $municipalityCity = $user->municipality_city ?? 'N/A';
    $issuedDate = optional($onlineId)->issued_at ? optional($onlineId)->issued_at->format('F d, Y') : now('Asia/Manila')->format('F d, Y');
    $validUntil = optional($onlineId)->expires_at
        ? optional($onlineId)->expires_at->format('F d, Y')
        : now('Asia/Manila')->copy()->addYear()->format('F d, Y');

    $photoUrl = $user->profile_photo_url;
    $defaultPhotoUrl = asset('images/city_of_general_trias_seal.png');
    $sealUrl = asset('images/city_of_general_trias_seal.png');

    $emergencyContactName = trim((string) ($user->emergency_contact_name ?? ''));
    $emergencyContactNumber = trim((string) ($user->emergency_contact_number ?? ''));
    $emergencyRelationship = trim((string) ($user->emergency_contact_relationship ?? ''));

    $returnAddress = trim((string) ($user->barangay ?? '')) !== ''
        ? 'Barangay Hall, ' . $user->barangay . ', ' . ($user->municipality_city ?? 'General Trias')
        : 'Barangay Hall, San Juan I, General Trias';

    $qrCodeDataUri = null;

    try {
        $qrPayload = implode('|', [
            'id=' . $idNumber,
            'name=' . $fullName,
            'account=' . $accountNumber,
            'barangay=' . $barangay,
            'city=' . $municipalityCity,
            'valid_until=' . $validUntil,
        ]);

        $qrOptions = new \chillerlan\QRCode\QROptions([
            'outputType' => \chillerlan\QRCode\Output\QROutputInterface::MARKUP_SVG,
            'outputBase64' => true,
            'eccLevel' => \chillerlan\QRCode\Common\EccLevel::M,
            'scale' => 4,
        ]);

        $qrCodeDataUri = (new \chillerlan\QRCode\QRCode($qrOptions))->render($qrPayload);
    } catch (\Throwable $e) {
        $qrCodeDataUri = null;
    }
@endphp

<style>
    .digital-id-shell {
        --id-base-width: 900;
        --id-base-height: 568;
        --id-scale: 1;
        width: 100%;
        margin: 0 auto;
        padding: 16px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        height: calc(var(--id-base-height) * 1px * var(--id-scale));
        overflow: visible;
    }

    .digital-id-scene {
        width: calc(var(--id-base-width) * 1px);
        height: calc(var(--id-base-height) * 1px);
        perspective: 1600px;
        transform-origin: top center;
        transform: scale(var(--id-scale));
        margin: 0 auto;
    }

    .digital-id-flipper {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 0;
        transform-style: preserve-3d;
        transition: transform 0.85s cubic-bezier(0.4, 0.2, 0.2, 1);
    }

    .digital-id-flipper.is-flipped {
        transform: rotateY(180deg);
    }

    .digital-id-face {
        position: absolute;
        inset: 0;
        background: #ffffff;
        border: 1px solid #d7e2ef;
        border-radius: 18px;
        box-shadow: 0 22px 50px rgba(8, 26, 52, 0.18);
        overflow: hidden;
        backface-visibility: hidden;
        display: flex;
        flex-direction: column;
    }

    .digital-id-back {
        transform: rotateY(180deg);
    }

    .digital-id-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 15px 20px;
        background: linear-gradient(135deg, #0a2d5e, #17447e);
        color: #ffffff;
        border-bottom: 2px solid rgba(255, 255, 255, 0.35);
    }

    .digital-id-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .digital-id-seal {
        width: 54px;
        height: 54px;
        border-radius: 999px;
        border: 2px solid rgba(255, 255, 255, 0.75);
        object-fit: cover;
        background: #ffffff;
    }

    .digital-id-title-top {
        margin: 0;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        opacity: 0.95;
    }

    .digital-id-title-main {
        margin: 3px 0;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .digital-id-title-sub {
        margin: 0;
        font-size: 11px;
        opacity: 0.92;
    }

    .digital-id-chip {
        padding: 6px 10px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        white-space: nowrap;
        background: rgba(255, 255, 255, 0.15);
    }

    .digital-id-body {
        padding: 20px;
        display: grid;
        grid-template-columns: 220px minmax(0, 1fr);
        gap: 18px;
        min-height: 418px;
        flex: 1;
    }

    .digital-id-photo-box {
        border: 1px solid #d0ddeb;
        border-radius: 14px;
        padding: 10px;
        background: #f8fbff;
    }

    .digital-id-photo-box img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 9px;
        border: 1px solid #d8e3ee;
        background: #eaf1f8;
    }

    .digital-id-photo-label {
        margin-top: 8px;
        text-align: center;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #4d5f77;
        font-weight: 700;
    }

    .digital-id-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 12px;
    }

    .digital-id-field {
        border: 1px solid #dbe6f2;
        border-radius: 10px;
        padding: 10px;
        background: #fcfdff;
    }

    .digital-id-label {
        margin: 0 0 4px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.9px;
        color: #607188;
        font-weight: 700;
    }

    .digital-id-value {
        margin: 0;
        font-size: 14px;
        color: #0f2540;
        font-weight: 700;
        line-height: 1.35;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .digital-id-signature-row {
        margin-top: 16px;
        display: flex;
        align-items: flex-end;
        gap: 16px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .digital-id-signature {
        min-width: 250px;
    }

    .digital-id-signature-line {
        border-bottom: 2px solid #1d3f67;
        height: 28px;
    }

    .digital-id-signature-text {
        margin-top: 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #586a81;
        font-weight: 700;
    }

    .digital-id-action {
        border: none;
        background: #0f3f75;
        color: #ffffff;
        border-radius: 9px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .digital-id-action:hover {
        background: #0a2f59;
    }

    .digital-id-back-content {
        padding: 20px;
        min-height: 418px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 210px;
        gap: 16px;
        flex: 1;
    }

    .digital-id-panel {
        border: 1px solid #dbe6f2;
        border-radius: 12px;
        padding: 13px;
        background: #fcfdff;
        margin-bottom: 12px;
    }

    .digital-id-panel-title {
        margin: 0 0 8px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0f3f75;
        font-weight: 800;
    }

    .digital-id-panel-text {
        margin: 0;
        font-size: 14px;
        color: #23374d;
        line-height: 1.45;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .digital-id-qr {
        border: 1px solid #cad8e8;
        border-radius: 12px;
        padding: 10px;
        background: #ffffff;
        text-align: center;
        align-self: start;
    }

    .digital-id-qr img {
        width: 180px;
        height: 180px;
        object-fit: contain;
        border: 1px solid #d8e2ee;
        border-radius: 8px;
        background: #ffffff;
    }

    .digital-id-qr-empty {
        width: 180px;
        min-height: 180px;
        border: 1px dashed #d8e2ee;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        font-size: 12px;
        color: #5a7089;
        background: #f8fbff;
    }

    .digital-id-qr-note {
        margin: 8px 0 0;
        font-size: 11px;
        color: #5a6f88;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
    }

    @media (max-width: 932px) {
        .digital-id-shell {
            --id-scale: min(1, calc((100vw - 32px) / (var(--id-base-width) * 1px)));
            height: calc(var(--id-base-height) * 1px * var(--id-scale));
        }
    }

    @media (max-width: 640px) and (orientation: portrait) {
        .digital-id-shell {
            padding: 12px;
            --id-scale: min(1, calc((100vw - 24px) / (var(--id-base-width) * 1px)));
            height: calc(var(--id-base-height) * 1px * var(--id-scale));
        }
    }

    @media print {
        .digital-id-action {
            display: none !important;
        }

        .digital-id-shell {
            max-width: 100%;
        }
    }
</style>

<div class="digital-id-shell">
    <div class="digital-id-scene">
        <div class="digital-id-flipper" id="resident-digital-id-card">
            <section class="digital-id-face digital-id-front">
                <div class="digital-id-header">
                    <div class="digital-id-header-left">
                        <img src="{{ $sealUrl }}" alt="Barangay seal" class="digital-id-seal">
                        <div>
                            <p class="digital-id-title-top">Republic of the Philippines</p>
                            <h3 class="digital-id-title-main">Barangay Digital ID</h3>
                            <p class="digital-id-title-sub">Official Resident Identification Card</p>
                        </div>
                    </div>
                    <span class="digital-id-chip">Front Side</span>
                </div>

                <div class="digital-id-body">
                    <div class="digital-id-photo-box">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Resident photo">
                        @else
                            <img src="{{ $defaultPhotoUrl }}" alt="Resident photo placeholder">
                        @endif
                        <div class="digital-id-photo-label">Resident Photo</div>
                    </div>

                    <div>
                        <div class="digital-id-grid">
                            <div class="digital-id-field">
                                <p class="digital-id-label">Full Name</p>
                                <p class="digital-id-value">{{ $fullName }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">ID Number</p>
                                <p class="digital-id-value">{{ $idNumber }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Account Number</p>
                                <p class="digital-id-value">{{ $accountNumber }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Birthdate</p>
                                <p class="digital-id-value">{{ $birthdate }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Gender</p>
                                <p class="digital-id-value">{{ $gender }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Barangay</p>
                                <p class="digital-id-value">{{ $barangay }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Municipality/City</p>
                                <p class="digital-id-value">{{ $municipalityCity }}</p>
                            </div>

                            <div class="digital-id-field">
                                <p class="digital-id-label">Issued Date</p>
                                <p class="digital-id-value">{{ $issuedDate }}</p>
                            </div>
                        </div>

                        <div class="digital-id-signature-row">
                            <div class="digital-id-signature">
                                <div class="digital-id-signature-line"></div>
                                <div class="digital-id-signature-text">Signature</div>
                            </div>

                            <div class="digital-id-field" style="min-width: 170px;">
                                <p class="digital-id-label">Valid Until</p>
                                <p class="digital-id-value">{{ $validUntil }}</p>
                            </div>

                            @if($showInlineFlipControls)
                                <button type="button" class="digital-id-action" onclick="toggleResidentDigitalIdCard()">Flip to Back</button>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="digital-id-face digital-id-back">
                <div class="digital-id-header">
                    <div class="digital-id-header-left">
                        <img src="{{ $sealUrl }}" alt="Barangay seal" class="digital-id-seal">
                        <div>
                            <p class="digital-id-title-top">Barangay Resident Card</p>
                            <h3 class="digital-id-title-main">Verification Details</h3>
                            <p class="digital-id-title-sub">Emergency and return information</p>
                        </div>
                    </div>
                    <span class="digital-id-chip">Back Side</span>
                </div>

                <div class="digital-id-back-content">
                    <div>
                        <div class="digital-id-panel">
                            <h4 class="digital-id-panel-title">Emergency Contact Details</h4>
                            <p class="digital-id-panel-text">Name: {{ $emergencyContactName !== '' ? $emergencyContactName : 'N/A' }}</p>
                            <p class="digital-id-panel-text">Relationship: {{ $emergencyRelationship !== '' ? $emergencyRelationship : 'N/A' }}</p>
                            <p class="digital-id-panel-text">Mobile: {{ $emergencyContactNumber !== '' ? $emergencyContactNumber : 'N/A' }}</p>
                        </div>

                        <div class="digital-id-panel">
                            <h4 class="digital-id-panel-title">Return Address</h4>
                            <p class="digital-id-panel-text">{{ $returnAddress }}</p>
                            <p class="digital-id-panel-text">Please surrender this card to the Barangay Office if found.</p>
                        </div>

                        @if($showInlineFlipControls)
                            <button type="button" class="digital-id-action" onclick="toggleResidentDigitalIdCard()">Flip to Front</button>
                        @endif
                    </div>

                    <div class="digital-id-qr">
                        @if($qrCodeDataUri)
                            <img src="{{ $qrCodeDataUri }}" alt="Resident QR code">
                        @else
                            <div class="digital-id-qr-empty">QR not available</div>
                        @endif
                        <p class="digital-id-qr-note">Scan for verification</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    function toggleResidentDigitalIdCard() {
        const card = document.getElementById('resident-digital-id-card');
        if (!card) return;
        card.classList.toggle('is-flipped');
    }

    function isResidentDigitalIdCardFlipped() {
        const card = document.getElementById('resident-digital-id-card');
        return card ? card.classList.contains('is-flipped') : false;
    }
</script>
