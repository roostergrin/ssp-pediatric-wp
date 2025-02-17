<section class="reviews-carousel">
    <div class="content">
        <div class="inner-content">
            <div class="review active">
                <p class="review-text">
                    “This product completely exceeded my expectations. Will buy again!”
                </p>
                <p class="review-author">– John Doe</p>
            </div>

            <!-- Second Review -->
            <div class="review">
                <p class="review-text">
                    “Fantastic quality and fast shipping. Highly recommend this store.”
                </p>
                <p class="review-author">– Jane Smith</p>
            </div>

            <!-- Third Review -->
            <div class="review">
                <p class="review-text">
                    “Great service, friendly staff, and an overall wonderful experience.”
                </p>
                <p class="review-author">– Bob Johnson</p>
            </div>

            <!-- Navigation Buttons -->
            <button class="carousel-btn prev-btn">Prev</button>
            <button class="carousel-btn next-btn">Next</button>

            <!-- Dots/Indicators -->
            <div class="dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
    <script>
  // Grab references to all relevant elements
  const reviews = document.querySelectorAll('.reviews-carousel .review');
  const dots = document.querySelectorAll('.reviews-carousel .dot');
  const prevBtn = document.querySelector('.reviews-carousel .prev-btn');
  const nextBtn = document.querySelector('.reviews-carousel .next-btn');

  let currentIndex = 0; // track which review is active

  // Function to show a review based on the index
  function showReview(index) {
    // Handle wrap-around (if index goes below 0 or beyond last element)
    if (index < 0) {
      index = reviews.length - 1;
    } else if (index >= reviews.length) {
      index = 0;
    }

    // Remove 'active' class from all reviews and dots
    reviews.forEach(review => review.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    // Add 'active' class to the current review and corresponding dot
    reviews[index].classList.add('active');
    dots[index].classList.add('active');

    // Update currentIndex
    currentIndex = index;
  }

  // Event listeners for navigation buttons
  nextBtn.addEventListener('click', () => {
    showReview(currentIndex + 1);
  });

  prevBtn.addEventListener('click', () => {
    showReview(currentIndex - 1);
  });

  // Event listeners for dots
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      showReview(i);
    });
  });

  // Optionally, you can auto-play the carousel by uncommenting the code below:
  /*
  setInterval(() => {
    showReview(currentIndex + 1);
  }, 3000);
  */
</script>

</section>