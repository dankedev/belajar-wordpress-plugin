jQuery(document).ready(function ($) {
  //code disini
  $(".wp-podcast-player").each(function () {
    $(this).mediaelementplayer({
      // API options
      features: ["playpause", "progress", "duration", "current"],
      alwaysShowControls: true,
      enableProgressTooltip: true,
      timeAndDurationSeparator: true,
      iPadUseNativeControls: true,
      iPhoneUseNativeControls: true,
      AndroidUseNativeControls: true,
      pauseOtherPlayers: true,
    });
  });
});
