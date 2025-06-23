document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.slide');
  let current = 0;
  const interval = 4000; // CSSのanimationと同じ秒数

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  // 最初のスライドを表示
  showSlide(current);

  // intervalごとに画像を切り替え
  setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
  }, interval);

    // #skillが画面に入ったらアニメーション開始
  const skillSection = document.getElementById('skill');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        skillSection.classList.add('animated');
        observer.unobserve(skillSection);
      }
    });
  }, { threshold: 0.5 });

  observer.observe(skillSection);
});

