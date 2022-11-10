jQuery(document).ready(function ($) {
  var file_frame;
  var previewAttribute = "div#wp-podcast-preview";
  var fileInputAttribute = "input#wp-podcast-file-upload-input";

  var MIMETYPES = {
    mp3: "audio/mpeg",
    m3a: "audio/mpeg",
    mp2a: "audio/mpeg",
    m4a: "audio/mp4",
    mp4: "audio/mp4",
  };

  //url.com/folder/nama-filenya-apa.mp3
  function getFiletype(filename) {
    return filename.split(".").pop();
  }

  //function to preview audio file
  function previewPodcastFile() {
    var valueFilePodcast = $(fileInputAttribute).val();

    if ("" !== valueFilePodcast) {
      var fileExt = getFiletype(valueFilePodcast);
      var audioType = MIMETYPES[getFiletype(fileExt)];
      if (audioType) {
        var htmlAudio = `<audio controls id="wp-podcast-player"><source src="${valueFilePodcast}" type="${audioType}"></audio>`;
        $(previewAttribute).html(htmlAudio);
        $("#wp-podcast-player").mediaelementplayer({
          features: ["playpause", "progress", "duration", "current"],
          alwaysShowControls: false,
          enableProgressTooltip: true,
          timeAndDurationSeparator: true,
          iPadUseNativeControls: true,
          iPhoneUseNativeControls: true,
          AndroidUseNativeControls: true,
          pauseOtherPlayers: true,
        });
      }
    }
  }

  previewPodcastFile();

  $("button[data-action=wp-podcast-upload-button]").click(function (e) {
    e.preventDefault();
    if (file_frame) {
      file_frame.open();
      return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
      title: "Select Podcast Audio",
      button: {
        text: "Select media",
      },
      multiple: false,
      library: {
        type: "",
      },
    });

    file_frame.on("select", function () {
      var attachment = file_frame.state().get("selection").first().toJSON();

      $(fileInputAttribute).val(attachment.url);
      previewPodcastFile();
    });

    file_frame.open();
  });
});
