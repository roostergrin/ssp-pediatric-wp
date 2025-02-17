 <section class="reviews-carousel  <?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
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
    
    
    // Dot navigation
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => showReview(i));
    });
  </script>
  <style>
    
  .reviews-carousel {
      width: 80%;
      margin: 2rem auto;
      font-family: sans-serif;
    }
    
    .reviews-carousel .content {
      position: relative;
      padding: 1rem;
      background-color: #f9f9f9;
    }
    
    .reviews-carousel .inner-content {
      position: relative;
    }
    
    .reviews-carousel .review {
      display: none; /* Hides all reviews by default */
      text-align: center;
      margin: 1rem 0;
      padding: 1rem;
      border: 1px solid #ccc;
      background-color: #fff;
    }
    
    .reviews-carousel .review.active {
      display: block; /* Only the active review shows */
    }
    
    .reviews-carousel .review .review-text {
      font-size: 1.2rem;
      line-height: 1.4;
      margin-bottom: 0.5rem;
    }
    
    .reviews-carousel .review .review-author {
      font-style: italic;
      color: #555;
    }
    
    .reviews-carousel .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: #333;
      color: #fff;
      border: none;
      padding: 0.5rem 1rem;
      cursor: pointer;
      font-size: 1rem;
    }
    
    .reviews-carousel .carousel-btn:hover {
      background-color: #555;
    }
    
    .reviews-carousel .carousel-btn.prev-btn {
      left: 0;
    }
    
    .reviews-carousel .carousel-btn.next-btn {
      right: 0;
    }
    
    .reviews-carousel .dots {
      text-align: center;
      margin-top: 1rem;
    }
    
    .reviews-carousel .dots .dot {
      display: inline-block;
      width: 10px;
      height: 10px;
      margin: 0 5px;
      background-color: #ccc;
      border-radius: 50%;
      cursor: pointer;
    }
    
    .reviews-carousel .dots .dot.active {
      background-color: #333;
    }
  </style>