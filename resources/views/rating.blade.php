<x-layout>
    <h1>RATE AMSTEL GRANDE</h1>
    <p class="subtitle">1 being the lowest and 5 being the highest</p>

    <form class="formDiv" id="rating-form">

        <div class="rating-group">
            <div class="rating-label">Aroma:</div>

            <div class="rateContainer2">
                <div class="numbers">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>
                <div class="rating-options">
                    <input type="radio" name="aroma" value="1">
                    <input type="radio" name="aroma" value="2">
                    <input type="radio" name="aroma" value="3">
                    <input type="radio" name="aroma" value="4">
                    <input type="radio" name="aroma" value="5" checked>
                </div>
            </div>
        </div>

        <div class="rating-group">
            <div class="rating-label">Taste:</div>

            <div class="rateContainer2">
                <div class="numbers">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>
                <div class="rating-options">
                    <input type="radio" name="taste" value="1">
                    <input type="radio" name="taste" value="2">
                    <input type="radio" name="taste" value="3">
                    <input type="radio" name="taste" value="4">
                    <input type="radio" name="taste" value="5" checked>
                </div>
            </div>
        </div>

        <div class="rating-group">
            <div class="rating-label">Smoothness:</div>

            <div class="rateContainer2">
                <div class="numbers">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>
                <div class="rating-options">
                    <input type="radio" name="smoothness" value="1">
                    <input type="radio" name="smoothness" value="2">
                    <input type="radio" name="smoothness" value="3">
                    <input type="radio" name="smoothness" value="4">
                    <input type="radio" name="smoothness" value="5" checked>
                </div>
            </div>
        </div>

        <div class="rating-group">
            <div class="rating-label">Would you recommend Amstel Grande:</div>

            <div class="rateContainer2">
                <div class="numbers">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>
                <div class="rating-options">
                    <input type="radio" name="recommendation" value="1">
                    <input type="radio" name="recommendation" value="2">
                    <input type="radio" name="recommendation" value="3">
                    <input type="radio" name="recommendation" value="4">
                    <input type="radio" name="recommendation" value="5" checked>
                </div>
            </div>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="newsletter" id="newsletter">
            I agree to share the above information to Amstel and receive Amstel newsletters
        </div>

        <button type="submit" class="submit-button">Submit</button>

        <div id="success-message" style="display: none; color: green; margin-top: 20px;">Thank you for your feedback!</div>
    </form>

</x-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rating-form').on('submit', function(e) {
            e.preventDefault();

            // Clear any previous success message
            $('#success-message').hide();

            // Get form data
            var formData = $(this).serialize();

            // AJAX request to submit rating
            $.ajax({
                url: '{{ route('rate') }}',  // Your backend URL to handle the rating submission
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Add CSRF token to the request headers
                },
                success: function(response) {
                    if (response.success) {
                        $('#success-message').show();  // Show the success message

                        // Redirect to the home page after 300 milliseconds
                        setTimeout(function() {
                            window.location.href = '{{ route('home') }}';  // Replace with your actual home page URL
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong, please try again.');
                }
                
            });
        });
    });
</script>
