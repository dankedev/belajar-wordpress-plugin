jQuery(function ($) {
  //kodenya nanti disini
  console.log($(".wp-post-reaction-button"));
  $(".wp-post-reaction-button").each(function () {
    $(this).on("click", function () {
      if ($(this).hasClass("has-clicked")) {
        return;
      }

      let container = $(this).parents(".wp-post-reaction-container");
      let nonce = container.attr("data-nonce");
      let post_id = container.attr("data-post-id");
      let reaction = $(this).attr("data-reaction");

      $.ajax({
        url: wp_post_reaction.ajaxurl,
        dataType: "json",
        type: "post",
        data: {
          action: "wp_post_reaction_make_reaction",
          reaction: reaction,
          _token: nonce,
          post_id: post_id,
        },
      });
    });
  });
});
