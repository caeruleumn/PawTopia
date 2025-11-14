<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fceee7;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Penting supaya bisa dorong footer ke bawah */
        }

        main {
            flex: 1;
            /* Isi semua ruang kosong di antara navbar dan footer */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .profile-card {
            background: white;
            width: 850px;
            border-radius: 25px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(245, 215, 197, 0.2);
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .avatar-wrapper {
            text-align: center;
        }

        .avatar {
            background-color: #f5d7c5;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 8px;
            font-size: 36px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5c3d2e;
            box-shadow: 0 8px 25px rgba(245, 215, 197, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.8);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .avatar:hover {
            transform: scale(1.05);
        }

        .avatar-note {
            font-size: 12px;
            color: #5c3d2e;
            opacity: 0.7;
        }

        .email {
            font-size: 15px;
            color: #5c3d2e;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .active-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fbb6a2;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            color: #5c3d2e;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(251, 182, 162, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .active-badge i {
            font-size: 14px;
            color: #5c3d2e;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, #eee, transparent);
            margin: 25px 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff4f0;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 12px;
            font-size: 14px;
            color: #5c3d2e;
            font-weight: 500;
            border: 1px solid rgba(245, 215, 197, 0.3);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .pets-section {
            text-align: left;
            margin-top: 25px;
        }

        .pets-section h4 {
            color: #5c3d2e;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .pet-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 15px;
            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.08),
                0 0 0 1px #eee;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .pet-card:hover {
            transform: translateY(-3px);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(245, 215, 197, 0.5);
        }

        .pet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #5c3d2e;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .pet-badge {
            background: #ffebd6;
            color: #5c3d2e;
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(255, 235, 214, 0.5);
        }

        .pet-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            font-size: 13px;
            color: #5c3d2e;
        }

        .pet-info div {
            background: #fff4f0;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(245, 215, 197, 0.3);
        }

        .pet-info strong {
            display: block;
            font-weight: 600;
            font-size: 10px;
            color: #8b5a3c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .pet-info div:nth-child(3) {
            grid-column: 1 / -1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .info-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .info-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .info-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .pet-card {
            animation-delay: 0.4s;
        }

        .info-item,
        .pet-card {
            animation: fadeInUp 0.6s ease-out both;
        }

        .logout-btn {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            border-radius: 20px;
            background: #fbb6a2;
            color: #5c3d2e;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(251, 182, 162, 0.3);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #f9a890;
            transform: translateY(-2px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    @include ('layouts.navbar')

    <main>
        <div class="profile-card">
            <div class="avatar-wrapper">
                <div class="avatar" id="avatar-preview"
                    style="background-image: url('default-avatar.png'); background-size: cover; background-position: center;">
                    <span id="avatar-initial">{{ strtoupper(substr(Auth::guard('member')->user()->name, 0, 1)) }}</span>
                    <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
                </div>
                <div class="avatar-note">Click to upload photo</div>
            </div>

            {{-- name --}}
            <div class="name" style="font-size: 20px; font-weight: 600; color: #5c3d2e; margin-bottom: 8px;">
                {{ Auth::guard('member')->user()->name }}


                {{-- email --}}
                <div class="email">{{ Auth::guard('member')->user()->email }}</div>

                {{-- status --}}
                <div class="active-badge">
                    Active Member <i class="fa-solid fa-paw"></i>
                </div>

                <hr>

                {{-- phone --}}
                <div class="info-item">
                    <span>Phone Number</span>
                    <span>{{ Auth::guard('member')->user()->phone }}</span>
                </div>

                {{-- address --}}
                <div class="info-item">
                    <span>Address</span>
                    <span>{{ Auth::guard('member')->user()->address ?? '-' }}</span>
                </div>

                {{-- created_at --}}
                <div class="info-item">
                    <span>Member Since</span>
                    <span>{{ Auth::guard('member')->user()->created_at->format('F d, Y') }}</span>
                </div>

                {{-- Pets Section --}}
                <div class="pets-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;">Registered Pets ({{ $pets->count() }})</h4>
                        <a href="{{ route('register.pets') }}" style="background: #fbb6a2; color: #5c3d2e; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(251, 182, 162, 0.3);">
                            + Add Pet
                        </a>
                    </div>

                    @forelse($pets as $pet)
                        <div class="pet-card">
                            <div class="pet-header">
                                <span>{{ $pet->name }}</span>
                                <span class="pet-badge" style="{{ $pet->type === 'dog' ? 'background: #FFE6CC;' : 'background: #E6F2FF;' }}">
                                    {{ $pet->type === 'dog' ? '🐕' : '🐈' }} {{ ucfirst($pet->type) }}
                                </span>
                            </div>
                            @if($pet->photo)
                            <div style="margin-top: 15px; text-align: center;">
                                <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}" style="max-width: 200px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            </div>
                            @endif
                            <br>
                            <div class="pet-info">
                                <div>
                                    <strong>BREED</strong>
                                    {{ $pet->breed ?? 'Not specified' }}
                                </div>
                                <div>
                                    <strong>AGE</strong>
                                    {{ $pet->age ? $pet->age . ' years' : 'Not specified' }}
                                </div>
                                <div>
                                    <strong>WEIGHT</strong>
                                    {{ $pet->weight ? number_format($pet->weight, 1) . ' kg' : 'Not specified' }}
                                </div>
                                @if($pet->medical_notes || $pet->special_requirements)
                                <div style="grid-column: 1 / -1;">
                                    <strong>NOTES</strong>
                                    <div style="margin-top: 5px; line-height: 1.5;">
                                        @if($pet->medical_notes)
                                            <div><span style="font-weight: 600;">Medical:</span> {{ $pet->medical_notes }}</div>
                                        @endif
                                        @if($pet->special_requirements)
                                            <div><span style="font-weight: 600;">Special:</span> {{ $pet->special_requirements }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 40px 20px; color: #8b5a3c;">
                            <div style="font-size: 48px; margin-bottom: 15px;">🐾</div>
                            <p style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">No Pets Registered Yet</p>
                            <p style="font-size: 14px; opacity: 0.7; margin-bottom: 20px;">Add your furry friends to start booking daycare services!</p>
                            <a href="{{ route('register.pets') }}" style="display: inline-block; background: #fbb6a2; color: #5c3d2e; padding: 12px 24px; border-radius: 20px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 15px rgba(251, 182, 162, 0.3);">
                                Add Your First Pet
                            </a>
                        </div>
                    @endforelse
                </div>

                <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" id="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
                </form>
            

            {{-- script avatar upload --}}
            <script>
                const avatarUpload = document.getElementById('avatar-upload');
                const avatarPreview = document.getElementById('avatar-preview');
                const avatarInitial = document.getElementById('avatar-initial');

                avatarPreview.addEventListener('click', () => {
                    avatarUpload.click();
                });

                avatarUpload.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            avatarPreview.style.backgroundImage = `url('${e.target.result}')`;
                            avatarInitial.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            </script>
        </div>
    </main>

    @include ('layouts.footer')
</body>

</html>