{{-- resources/views/home.blade.php --}}
<x-layout>

    {{-- Step 1: Video Page --}}
    <main id="page-video" class="page active" aria-hidden="false">
        <div class="video-container">
            <video id="intro-video" playsinline preload="auto">
                <source src="{{ asset('public/videos/intro.webm') }}" type="video/webm">
                <source src="{{ asset('public/videos/intro.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Tap to Start Overlay -->
            <div id="video-overlay">
                <div class="overlay-content">
                    <div class="play-icon">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="35,25 75,50 35,75" fill="#ffe400"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{-- Step 2: Register Now Page --}}
{{--    <main id="page-register-now" class="page page-flex" aria-hidden="true">--}}
{{--        <h1 class="title">SECRET PARTY 2.0</h1>--}}
{{--        <button class="register-now-btn" id="btn-to-terms">REGISTER NOW</button>--}}
{{--    </main>--}}

    {{-- Step 3: Terms / Instructions Page --}}
    <main id="page-steps" class="page" aria-hidden="true">
        <h1 class="yellow-title">SECRET PARTY 2.0</h1>
        <!--<p class="subtitle">Steps to receive your exclusive invitation:</p>-->

        <ul class="steps" role="list">
            <!--<li>Enter your details in the next page.</li>-->
            <!--<li>Click a picture of yourself with your favourite Secret Temptation fragrance.</li>-->
            <!--<li>Upload the picture on your social media with <span class="highlight">#SecretParty</span> and tag two of-->
            <!--    your girlies — <em>this is very important!</em></li>-->
            <li>Follow the official page of Secret Temptation @secrettemptationofficial.</li>
            <!--<li>And voila! Guess what? You might get a chance to receive an exclusive invite. – meet your favourite-->
            <!--    stars and party hard with them.-->
            <!--</li>-->
        </ul>


        <!--<div class="centered bottom-btn">-->
        <!--    <p class="subtitle" style="margin-top:8px; font-size:18px">READY TO BE A PART OF THE MOST GLAMOROUS-->
        <!--        PARTY?</p>-->
        <!--    <button class="btn" id="btn-to-register">PROCEED</button>-->
        <!--</div>-->
    </main>

    {{-- Step 4: Registration Page --}}
    <main id="page-register" class="page" aria-hidden="true">
        <h1 class="yellow-title">SECRET PARTY 2.0</h1>
        <p class="subtitle">REGISTER YOURSELF</p>

        <form id="register-form" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <label for="name">Name:</label>
                <input id="name" type="text" name="name" placeholder="Enter your full name"/>
                <span class="error-message" id="name-error"></span>
            </div>

            <div class="form-row">
                <label for="email">E-mail ID:</label>
                <input id="email" type="email" name="email" placeholder="you@example.com"/>
                <span class="error-message" id="email-error"></span>
            </div>

            <div class="form-row">
                <label for="phone">Contact Number:</label>
                <input id="phone" type="tel" name="phone" placeholder="9876543210"/>
                <span class="error-message" id="phone-error"></span>
            </div>

            <div class="form-row">
                <label for="instagram">Instagram ID:</label>
                <input id="instagram" type="text" name="instagram" placeholder="yourusername"/>
                <span class="error-message" id="instagram-error"></span>
            </div>
            
            <div class="centered bottom-btn">
                <p class="subtitle" style="margin-top:8px; font-size:18px">UPLOAD A PICTURE OF YOU WITH YOUR FAVOURITE
                    SECRET TEMPTATION FRAGRANCE</p>
                <button id="register-btn" type="submit" class="btn">CONTINUE</button>
                <button id="open-instagram" class="btn" style="display:none">INSTAGRAM</button>
            </div>
        </form>
    </main>

    {{-- Step 5: Thank You Page --}}
    <main id="page-thankyou" class="page" aria-hidden="true" style="text-align:center;">
        <h1 class="yellow-title">SECRET PARTY 2.0</h1>
        <p class="subtitle">Thank You for Registering!</p>
        <p class="subtitle">You are in the queue!</p>
        <div class="centered bottom-btn">
            <button id="btn-go-instagram" class="btn">Go to Instagram</button>
        </div>
    </main>

</x-layout>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        const video = document.getElementById('intro-video');
        const overlay = document.getElementById('video-overlay');

        // Wait for user tap
        overlay.addEventListener('click', function () {
            video.muted = false; // Enable sound
            video.play().catch(err => console.error('Video play failed:', err));
            overlay.classList.add('hidden'); // Fade out overlay
        });
    });
    $(function () {

        const $pageVideo = $('#page-video');
        const $pageRegisterNow = $('#page-register-now');
        const $pageSteps = $('#page-steps');
        const $pageRegister = $('#page-register');

        const videoEl = document.getElementById('intro-video');

        /**
         * Utility to switch between steps with smooth fade
         */
        function showPage($pageToShow) {
            $('.page').removeClass('active').attr('aria-hidden','true');
            setTimeout(() => {
                $pageToShow.addClass('active').attr('aria-hidden','false');
            }, 50);
        }

        /**
         * Step 1: Video ends → show instructions
         */
        videoEl.addEventListener('ended', function () {
            showPage($pageSteps);
        });

        /**
         * Step 3: Go to registration
         */
        $('#btn-to-terms').on('click', function () {
            showPage($pageSteps);
        });


        /**
         * Step 2: Go to registration
         */
        $('#btn-to-register').on('click', function () {
            showPage($pageRegister);
        });

        /**
         * Photo upload preview
         */
        $('#photoInput').on('change', function (e) {
            const f = e.target.files[0];
            const $preview = $('#preview');
            if (!f) return $preview.text('No file selected');
            if (!f.type.startsWith('image/')) return $preview.text('Please choose an image file.');
            const url = URL.createObjectURL(f);
            $preview.html(`<img src="${url}" alt="chosen photo" style="max-width:100%; border-radius:10px; margin-top:8px; display:block"/>`);
        });

        /**
         * Form submission → redirect to Instagram
         */
        $('#register-form').on('submit', function (e) {
            e.preventDefault();
            $('.error-message').text('');
            $('input').removeClass('input-error'); // remove red borders
            
            let instagram = $('#instagram').val().trim();

            // Instagram username regex
            const instaRegex = /^(?!.*\.\.)(?!.*\.$)[a-zA-Z0-9._]{1,30}$/;
        
            if (instagram !== "" && !instaRegex.test(instagram)) {
                $('#instagram-error').text("Please enter a valid Instagram username.");
                $('#instagram').addClass('input-error');
                return; // stop form submission
            }

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("register") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function (response) {
                    if (response.success) {
                        showPage($('#page-thankyou'));
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;

                    if (errors?.name) {
                        $('#name-error').text(errors.name[0]);
                        $('#name').addClass('input-error');
                    }
                    if (errors?.email) {
                        $('#email-error').text(errors.email[0]);
                        $('#email').addClass('input-error');
                    }
                    if (errors?.phone) {
                        $('#phone-error').text(errors.phone[0]);
                        $('#phone').addClass('input-error');
                    }
                    if (errors?.instagram) {
                        $('#instagram-error').text(errors.instagram[0]);
                        $('#instagram').addClass('input-error');
                    }
                }
            });
        });
        
        $('input').on('focus', function () {
            $(this).removeClass('input-error');
            $(this).next('.error-message').text('');
        });

        $('#btn-go-instagram').on('click', function() {
            $('#open-instagram').click(); // reuse existing logic
        });

        $('#open-instagram').on('click', function () {
            let username = "secrettemptationofficial"; // Replace with your company IG username

            if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                // Try to open in Instagram app
                //window.location.href = "instagram://user?username=" + username;
                window.location.href = "instagram://story-camera";

                // Fallback to browser if app isn't installed
                setTimeout(function () {
                    window.location.href = "https://www.instagram.com/" + username + "/";
                }, 1000);
            } else {
                // Desktop just goes to the Instagram page
                window.location.href = "https://www.instagram.com/" + username + "/";
            }
        });

    });
</script>
