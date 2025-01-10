<section class="signs_and_sypmtoms<?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="signs_and_symptoms_content">
        <div class="signs_and_symptoms__cards">
            <div class="signs_and_symptoms__card">
                <h2 class="signs_and_symptoms__header">Baby signs and symptoms</h2>
            </div>
            <div class="signs_and_symptoms__card">
                <h2 class="signs_and_symptoms__header">Mother's signs and symptoms</h2>
            </div>
            <div class="signs_and_symptoms__card"></div>
        </div>
        <div class="signs_and_symptoms__images"></div>
    </div>
<style>
    .text-white {
        color: white;
    }
    .signs_and_sypmtoms_content {
        display: flex;
    }
    .signs_and_sypmtoms_cards {
        display: flex;
        flex-direction: column;
        gap: 3rem;
    }
    .signs_and_sypmtoms_card {
        background: white;
        color: black;
        border-radius: 5px;
    }
</style>
</section>
