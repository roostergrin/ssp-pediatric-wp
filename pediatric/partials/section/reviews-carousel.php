 <section class="reviews-carousel">
    <div class="content">
      <div class="inner-content">
        <div class="review active">
          <p class="review-text">
            “This product completely exceeded my expectations. Will buy again!”
          </p>
          <p class="review-author">– John Doe</p>
        </div>

        <div class="review">
          <p class="review-text">
            “Fantastic quality and fast shipping. Highly recommend this store.”
          </p>
          <p class="review-author">– Jane Smith</p>
        </div>

        <div class="review">
          <p class="review-text">
            “Great service, friendly staff, and an overall wonderful experience.”
          </p>
          <p class="review-author">– Bob Johnson</p>
        </div>

        <button class="carousel-btn prev-btn">Prev</button>
        <button class="carousel-btn next-btn">Next</button>

        <div class="dots">
          <span class="dot active"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
    </div>
  </section>
   <script>
    const reviews = document.querySelectorAll('.reviews-carousel .review');
    const dots = document.querySelectorAll('.reviews-carousel .dot');
    const prevBtn = document.querySelector('.reviews-carousel .prev-btn');
    const nextBtn = document.querySelector('.reviews-carousel .next-btn');
    
    let currentIndex = 0; // Which review/dot is active
    
    function showReview(index) {
      // Wrap-around logic
      if (index < 0) {
        index = reviews.length - 1;
      } else if (index >= reviews.length) {
        index = 0;
      }
      // Remove 'active' class from everything
      reviews.forEach(r => r.classList.remove('active'));
      dots.forEach(d => d.classList.remove('active'));
      // Add 'active' class to the current review & dot
      reviews[index].classList.add('active');
      dots[index].classList.add('active');
      currentIndex = index;
    }
    
    nextBtn.addEventListener('click', () => showReview(currentIndex + 1));
    prevBtn.addEventListener('click', () => showReview(currentIndex - 1));
    
    // Dot navigation
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => showReview(i));
    });
  </script>