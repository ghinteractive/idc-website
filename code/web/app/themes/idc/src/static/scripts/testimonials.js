(function ($) {
  $(function () {

    //check if any testimonials
    if($('.c-testimonials-block').length > 0){
      //check for simple slider-based testimonials
      const numSimpleTestimonials = $('.c-testimonials-block--simple .testimonial').length;
      if(numSimpleTestimonials > 0){
        //resize handler to handle resize of testimonials 
        $(window).on('resize', throttleDebounce.throttle(150, checkTesimonialHeight));

        //setTimeout to handle CSS style loading
        setTimeout(function(){checkTesimonialHeight()},750);
      }
      //check for video testimonials
      const numVideoTestimonials = $('.c-testimonials-block--video .testimonial').length;

      //event wiring
      if(numSimpleTestimonials > 0 || numVideoTestimonials > 0){
        wireTestimonialControls();
      }
    }

    //checks the height of simple testimonials
    function checkTesimonialHeight(){
      //holder for tallest testimonials
      let maxHeight = 0;
      //padding on testimonials (in px specifically)
      let padding = 32;
      
      //loop through each testimonial, get the height of the figure element which is the most consistent element for height
      $('.c-testimonials-block--simple .testimonial').each(function(){
        let figureHeight = $(this).find("figure").height();
        if(figureHeight + padding > maxHeight){
          maxHeight = figureHeight + padding;
        }
      });

      //set the height of all testimonials to the maxHeight
      $('.c-testimonials-block--simple .testimonial').height(maxHeight);

      //set the height of the primary testimonial wrapper to the maxHeight and add room for the CTA button (in px specifically)
      $('.c-testimonials-block--simple .wrapper').height(maxHeight + 75);
    }

    //wires the testimonial up and down buttons events for each testimonial block on the page
    function wireTestimonialControls(){
      //simple testimonials
      if($('.c-testimonials-block--simple').length > 0){
        $('.c-testimonials-block--simple').each(function(){
          let uid = $(this).attr("data-testimonial-id");
          $(".simpleButtonUp[data-testimonial-id='" + uid + "']").on('click',function(){
            shiftTestimonial(uid,'up');
          });
          $(".simpleButtonDown[data-testimonial-id='" + uid + "']").on('click',function(){
            shiftTestimonial(uid,'down');
          });
        });
      }

      //video testimonials
      if($('.c-testimonials-block--video').length > 0){
        
        $('.c-testimonials-block--video').each(function(){
          let uid = $(this).attr("data-testimonial-id");
          $(".poster[data-testimonial-id='" + uid + "']").on('click',function(){
            playVideo(uid);
          });
        });
      }
    }

    


    //hide poster and play video
    function playVideo(uid){
      //reference to the poster photo overlay with play button
      let posterElem =  $(".poster[data-testimonial-id='" + uid + "']");
      //iframe reference - used for Vimeo API
      let iframe = $("iframe[data-testimonial-id='" + uid + "'] ");
      //embed reference
      let embed = $(".embed-container[data-testimonial-id='" + uid + "']");
      
      //hide the poster on click of element
      posterElem.hide();

      //check video type
      let videoType = embed.attr("data-type");

      //play video using proper API
      switch(videoType){
        case "youtube":
          //dynamic variables defined in the testimonials.php used to reference YouTube API Player
          if($('#'+uid).attr('data-ready')){
            window['player_'+uid].playVideo();
          }
          break;
        case "vimeo":
          let player = "";
          player = new Vimeo.Player(iframe);
          player.play();
          break;
      }
    }

    //shift a testimonials stack up or down
    function shiftTestimonial(uid,dir){
      let testim = $(".c-testimonials-block[data-testimonial-id='" + uid + "']");
      let numSimpleTestimonials = testim.find('.testimonial').length;

      testim.find('.testimonial').each(function(){
                
        let dataInversePos = parseInt($(this).attr('data-inverse-index'));
        let dataPos = parseInt($(this).attr('data-index'));

        let newDataInversePos = 0;
        let newDataPos = 0;

        //there can only be 10 testimonials per block - because of this, we track two different sets
        //of indices. data-index counts down from 10 (for z-index purposes). data-inverse-index counts
        //up from 1 and is used to identify the testimonials counting down from the first visible

        switch(dir){
          case "up":
            newDataInversePos = dataInversePos - 1;
            newDataPos = dataPos + 1;
          
            if(dataPos + 1 <= 10){
              $(this).attr('data-index',newDataPos);
              $(this).attr('data-inverse-index',newDataInversePos);
            }else{
              popTop($(this),numSimpleTestimonials,10 - numSimpleTestimonials + 1);
            }
            break;
          case "down":
            newDataInversePos = dataInversePos + 1;
            newDataPos = dataPos - 1;
          
            if(dataPos - 1 >= 10 - numSimpleTestimonials + 1){
              $(this).attr('data-index',newDataPos);
              $(this).attr('data-inverse-index',newDataInversePos);
            }else{
              popTop($(this),1,10);
            }
            break;
        }
      });
    }

    //handles the z-index, data-index and data-inverse-index property/attributes
    function popTop(tar,newInversIndex,newIndex){
      let targetElem = tar;
      targetElem.attr('data-index',newIndex);
      targetElem.attr('data-inverse-index',newInversIndex);
    }
  });
})(jQuery);

//init function called by YouTube API when ready
function onYouTubeIframeAPIReady() {
  //get all YouTube videos
  let ytv = document.querySelectorAll("[data-type='youtube']"), i;
  //loop through all YouTube videos and create API players for each
  for (i = 0; i < ytv.length; ++i) {
    let id = ytv[i].getAttribute('data-testimonial-id');
    let videoID = ytv[i].getAttribute('data-video-id');
    window['player_'+id] = new YT.Player(id, {
                                                videoId: videoID,
                                                playerVars: {
                                                    'playsinline': 1
                                                },
                                                events: {
                                                    'onReady': window['onPlayerReady_'+id]
                                                }
                                                });
  }
  //each new video player calls an onReady function defined in testimonials.php
}