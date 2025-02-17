<section class="reviews-carousel  <?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="content">
        <div class="inner-content">
            <div class="review active">
                <!-- Google Review Stars -->
                <div class="review-stars">
                    <!-- 
            For demonstration, these are 5 identical filled stars. 
            In practice, you can dynamically show half-stars, 
            different counts, etc. 
          -->
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                </div>

                <p class="review-text">
                    We had an incredible experience at Smiles in Motion Pediatric Dentistry. We
brought our newborn in for a lip and tongue tie revision, and from the initial
assessment to the procedure itself, everything was handled with care and
professionalism. As chiropractors who have referred many children here, it was
amazing to experience their expertise firsthand as parents. We’re so grateful to have
this invaluable service available in our area. A big thank you to Dr. Winn and the
entire SIM team for providing such exceptional care!”
                </p>
                <p class="review-author">– Courtney K. </p>
            </div>

            <div class="review">
                <!-- Google Review Stars -->
                <div class="review-stars">
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                </div>

                <p class="review-text">
                    “My son had a lip, tongue; and cheek tie. We had a consultation with the SIM Team
and the laser procedure done. The entire experience was great even though I was so
nervous and worried about the procedure. They made sure all of my questions were
answered and were very thorough and kind. They even called us in the afternoon
after the procedure to check in with us. I would definitely recommend seeing the
team at Smiles in Motion"
                </p>
                <p class="review-author">– Kayla H.</p>
            </div>

            <div class="review">
                <!-- Google Review Stars -->
                <div class="review-stars">
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                    <img src="/wp-content/uploads/star.svg" alt="Star" />
                </div>

                <p class="review-text">
                    “We came to Smiles in Motion desperate for answers as to why our little 6 week old
was struggling to latch and feed which was resulting in sleepless nights full of gas
and acid reflux. We found out that our girl had three oral ties and from there we
were able to get them released and now we are on the road to recovery with high
hopes for her ability to latch and feed. From the moment we stepped into the office,
we were treated with kindness and compassion. We were given the space we’d need
to care for our little girl comfortably during our visit and had everything that was to
happen explained in great detail so that we felt comfortable and confident with our
decision. When it came time for the procedure I felt incredibly comfortable with
them releasing our daughter’s ties and could tell that she felt comfortable and safe
with them, which for me as a mom, is the most important thing! I can’t thank the
team enough for the care and support we have received!”
                </p>
                <p class="review-author">– Olivia H.</p>
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
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .review-stars {
        display: flex;
        justify-content: flex-end;
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
        display: none;
        /* Hides all reviews by default */
        text-align: center;
        margin: 1rem 0;
        padding: 1rem;
        border: 1px solid #ccc;
        background-color: #fff;
    }

    /* Only the active review is visible */
    .reviews-carousel .review.active {
        display: block;
    }

    /* Star container */
    .reviews-carousel .review .review-stars {
        margin-bottom: 0.75rem;
    }

    /* Example: if you're using images for stars */
    .reviews-carousel .review .review-stars img {
        width: 40px;
        /* Adjust size as needed */
        height: 40px;
        /* Adjust size as needed */
        margin: 0 2px;
    }

    /* If you're using text-based stars (like ★), you can style .review-stars span */
    .reviews-carousel .review-stars span {
        color: #ffd700;
        /* gold color, for example */
        font-size: 1.2rem;
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