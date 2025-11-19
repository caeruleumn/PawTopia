@include('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Your Pets - PawTopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff0eb 0%, #ffffff 100%);
            margin: 0;
            padding: 20px;
            color: #5C4033;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            color: #5C4033;
            margin-bottom: 10px;
        }

        .header h1 span {
            color: #F07F62;
        }

        .header p {
            color: #8A6552;
            font-size: 16px;
        }

        .pets-container {
            margin-bottom: 30px;
        }

        .pet-card {
            background: #fff0eb;
            border: 2px solid #f4a28c;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
        }

        .pet-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .pet-number {
            font-size: 18px;
            font-weight: 600;
            color: #5C4033;
        }

        .remove-pet-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .remove-pet-btn:hover {
            background: #c82333;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .form-field.full-width {
            grid-column: 1 / -1;
        }

        .form-field label {
            font-size: 14px;
            font-weight: 500;
            color: #5C4033;
            margin-bottom: 8px;
        }

        .form-field input,
        .form-field select,
        .form-field textarea {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }

        .form-field input:focus,
        .form-field select:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: #F07F62;
        }

        .form-field textarea {
            resize: vertical;
            min-height: 80px;
        }

        .photo-upload {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .photo-upload:hover {
            border-color: #F07F62;
            background: #fff5f2;
        }

        .photo-upload input[type="file"] {
            display: none;
        }

        .photo-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .photo-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            margin-top: 10px;
            display: none;
        }

        .photo-preview.show {
            display: block;
        }

        .add-pet-btn {
            background: white;
            color: #5C4033;
            border: 2px dashed #5C4033;
            padding: 15px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .add-pet-btn:hover {
            background: #5C4033;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #5C4033;
            color: white;
        }

        .btn-primary:hover {
            background: #7b5b4b;
        }

        .btn-secondary {
            background: white;
            color: #5C4033;
            border: 2px solid #5C4033;
        }

        .btn-secondary:hover {
            background: #f9f5f3;
        }

        .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .skip-info {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1><span>🐾</span> Register Your Pets</h1>
        <p>Tell us about your furry friends! You can add dogs and cats.</p>
    </div>

    <form id="petsForm">
        <div class="pets-container" id="petsContainer">
            <!-- Pet cards will be added here -->
        </div>

        <button type="button" class="add-pet-btn" onclick="addPetCard()">
            + Add Another Pet
        </button>

        <div class="action-buttons">
            <button type="submit" class="btn btn-primary" id="submitBtn">Complete Registration</button>
            <button type="button" class="btn btn-secondary" onclick="skipPets()">Skip for Now</button>
        </div>

        <div id="loadingMessage" style="display: none; text-align: center; margin-top: 20px; color: #F07F62; font-weight: 600;">
            🐾 Registering your pets... Please wait.
        </div>

        <div class="skip-info">
            You can always add pets later from your profile
        </div>
    </form>
</div>

<script>
    let petCount = 0;

    // Add first pet card on load
    window.onload = function() {
        addPetCard();
    };

    function addPetCard() {
        petCount++;
        const container = document.getElementById('petsContainer');
        
        const petCard = document.createElement('div');
        petCard.className = 'pet-card';
        petCard.id = `pet-${petCount}`;
        
        petCard.innerHTML = `
            <div class="pet-card-header">
                <div class="pet-number">Pet #${petCount}</div>
                ${petCount > 1 ? `<button type="button" class="remove-pet-btn" onclick="removePetCard(${petCount})">Remove</button>` : ''}
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Pet Name <span style="color: red;">*</span></label>
                    <input type="text" name="pets[${petCount}][name]" placeholder="e.g., Max" required>
                </div>

                <div class="form-field">
                    <label>Pet Type <span style="color: red;">*</span></label>
                    <select name="pets[${petCount}][type]" required>
                        <option value="">Select Type</option>
                        <option value="dog">🐕 Dog</option>
                        <option value="cat">🐈 Cat</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Breed (Optional)</label>
                    <input type="text" name="pets[${petCount}][breed]" placeholder="e.g., Golden Retriever">
                </div>

                <div class="form-field">
                    <label>Age (Years)</label>
                    <input type="number" name="pets[${petCount}][age]" placeholder="e.g., 3" min="0" max="30" step="1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Weight (kg)</label>
                    <input type="number" name="pets[${petCount}][weight]" placeholder="e.g., 15.5" min="0" max="100" step="0.1">
                </div>

                <div class="form-field">
                    <label>Photo</label>
                    <div class="photo-upload" onclick="document.getElementById('photo-${petCount}').click()">
                        <input type="file" id="photo-${petCount}" name="pets[${petCount}][photo]" accept="image/*" onchange="previewPhoto(${petCount}, this)">
                        <label for="photo-${petCount}" class="photo-upload-label">
                            <div class="icon">📷</div>
                            <span>Click to upload photo</span>
                        </label>
                        <img id="preview-${petCount}" class="photo-preview" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field full-width">
                    <label>Medical Notes (Optional)</label>
                    <textarea name="pets[${petCount}][medical_notes]" placeholder="Any medical conditions, allergies, or special care needed..."></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field full-width">
                    <label>Special Requirements (Optional)</label>
                    <textarea name="pets[${petCount}][special_requirements]" placeholder="Special food, habits, or preferences..."></textarea>
                </div>
            </div>
        `;
        
        container.appendChild(petCard);
    }

    function removePetCard(id) {
        const card = document.getElementById(`pet-${id}`);
        if (card) {
            card.remove();
        }
    }

    function previewPhoto(petId, input) {
        const preview = document.getElementById(`preview-${petId}`);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function skipPets() {
        if (confirm('Are you sure you want to skip pet registration? You can add pets later from your profile.')) {
            window.location.href = '{{ route("profile") }}';
        }
    }

    // Handle form submission
    document.getElementById('petsForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const loadingMsg = document.getElementById('loadingMessage');
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        loadingMsg.style.display = 'block';
        
        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            // Submit each pet individually
            const petCards = document.querySelectorAll('.pet-card');
            let successCount = 0;
            let errors = [];

            for (let i = 0; i < petCards.length; i++) {
                const petIndex = i + 1;
                
                // Get required fields
                const petName = formData.get(`pets[${petIndex}][name]`);
                const petType = formData.get(`pets[${petIndex}][type]`);
                
                // Skip if name or type is empty
                if (!petName || !petType) {
                    console.log(`Skipping pet ${petIndex}: name or type is empty`);
                    continue;
                }

                const petData = new FormData();
                
                // Collect pet data
                petData.append('name', petName);
                petData.append('type', petType);
                petData.append('breed', formData.get(`pets[${petIndex}][breed]`) || '');
                petData.append('age', formData.get(`pets[${petIndex}][age]`) || '');
                petData.append('weight', formData.get(`pets[${petIndex}][weight]`) || '');
                petData.append('medical_notes', formData.get(`pets[${petIndex}][medical_notes]`) || '');
                petData.append('special_requirements', formData.get(`pets[${petIndex}][special_requirements]`) || '');
                
                const photoFile = document.getElementById(`photo-${petIndex}`).files[0];
                if (photoFile) {
                    petData.append('photo', photoFile);
                }

                console.log(`Submitting pet ${petIndex}:`, petName, petType);

                // Submit to API
                const response = await fetch('{{ route("pets.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: petData
                });

                const result = await response.json();
                console.log(`Response for pet ${petIndex}:`, result);

                if (response.ok) {
                    successCount++;
                } else {
                    errors.push(`Pet ${petIndex} (${petName}): ${result.message || 'Failed'}`);
                }
            }

            if (successCount > 0) {
                alert(`Successfully registered ${successCount} pet(s)! Welcome to PawTopia! 🐾`);
                window.location.href = '{{ route("profile") }}';
            } else {
                const errorMsg = errors.length > 0 
                    ? 'Errors:\n' + errors.join('\n')
                    : 'Failed to register pets. Please make sure to fill in pet name and type.';
                alert(errorMsg);
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Complete Registration';
                loadingMsg.style.display = 'none';
            }

        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
            
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Complete Registration';
            loadingMsg.style.display = 'none';
        }
    });
</script>

@include('layouts.footer')

</body>
</html>
