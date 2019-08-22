 $(document).on('ready', function() {


function sliderInit(){
    $('.responsive').slick({
  dots: false,
  infinite: false,
  speed: 500,
  slidesToShow: 5,
  slidesToScroll: 5,
  prevArrow: $('#left'),
  nextArrow: $('#right'),
  responsive: [
  {
      breakpoint: 1550,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
        infinite: true,
        dots: false,
        speed: 500,
        arrows: true
      }
    },
  {
      breakpoint: 1224,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        infinite: true,
        dots: false,
        speed: 500,
        arrows: true
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false,
        speed: 500,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        dots: false,
        speed: 500,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        dots: false,
        speed: 500,
      }
    }
  ]
});
};
sliderInit();

      $('.multiple-items').slick({
          infinite: false,
          slidesToShow: 4,
          slidesToScroll: 4,
          arrows: true
        });
      $(".vertical-center-4").slick({
        dots: true,
        vertical: true,
        centerMode: true,
        slidesToShow: 4,
        slidesToScroll: 2
      });
      $(".vertical-center-3").slick({
        dots: true,
        vertical: true,
        centerMode: true,
        slidesToShow: 3,
        slidesToScroll: 3
      });
      $(".vertical-center-2").slick({
        dots: false,
        infinite: false,
        vertical: true,
        centerMode: false,
        slidesToShow: 2,
        slidesToScroll: 2,

      });
      $(".vertical-center").slick({
        dots: true,
        vertical: true,
        centerMode: true,
      });
      $(".vertical").slick({
        dots: true,
        vertical: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true
      });
      $(".regular").slick({
        dots: true,
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 3
      });
      $(".center").slick({
        infinite: true,
        centerMode: true,
        centerPadding: '60px',
        slidesToShow: 5,
        slidesToScroll: 3
      });
      $(".variable").slick({
        dots: true,
        infinite: true,
        variableWidth: true
      });
      $(".lazy").slick({
        lazyLoad: 'ondemand', // ondemand progressive anticipated
        infinite: true
      });
      
    });




