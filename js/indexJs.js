document.addEventListener("DOMContentLoaded", () => {
  gsap.registerPlugin(ScrollTrigger);
  
  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  });

  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => lenis.raf(time * 1000));
  gsap.ticker.lagSmoothing(0);

  gsap.from('.parallax__title', {
    y: 50,
    opacity: 0,
    duration: 1,
    delay: 0.5,
  });

  gsap.from('.parallax__subtitle', {
    y: 30,
    opacity: 0,
    duration: 1,
    delay: 0.8,
  });

  gsap.from('.cta-button', {
    y: 20,
    opacity: 0,
    duration: 1,
    delay: 1.1,
  });

  const sections = document.querySelectorAll('.section');
  sections.forEach((section) => {
    gsap.from(section, {
      scrollTrigger: {
        trigger: section,
        start: "top 80%",
      },
      y: 50,
      opacity: 0,
      duration: 1,
    });
  });

});