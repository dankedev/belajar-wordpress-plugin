jQuery(document).ready(function ($) {
  var file_frame;
  var buttonAttribute = "button[data-action=wp-podcast-upload-button]";
  var previewAttribute = "div#wp-podcast-preview";
  var fileInputAttribute = "input#wp-podcast-file-upload-input";

  var MIMETYPES = {
    mp3: "audio/mpeg",
    m3a: "audio/mpeg",
    mp2a: "audio/mpeg",
    m4a: "audio/mp4",
    mp4: "audio/mp4",
  };

  function getFiletype(filename) {
    return filename.split(".").pop();
  }

  function previewPorcastFile() {
    var valueFilePodcast = $(fileInputAttribute).val();

    if ("" !== valueFilePodcast) {
      var fileExt = getFiletype(valueFilePodcast);
      var audioType = MIMETYPES[getFiletype(fileExt)];
      if (audioType) {
        var htmlAudio = `<audio controls id="wp-podcast-player"><source src="${valueFilePodcast}" type="${audioType}"></audio>`;
        $(previewAttribute).html(htmlAudio);
        $("#wp-podcast-player").mediaelementplayer({
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
      }
    }
  }
  previewPorcastFile();

  // Uploading files

  $(buttonAttribute).click(function (e) {
    e.preventDefault();
    // jika sudah pernah ada file_frame nya, langsung reopen dialog wp.media -nya
    if (file_frame) {
      file_frame.open();
      return;
    }
    // Extend the wp.media object

    file_frame = wp.media.frames.file_frame = wp.media({
      title: "Select Audio/Video",
      button: {
        text: "Select media",
      },
      multiple: false,
      library: {
        type: "",
      },
    });
    // Ketika file telah dipilih, dapatkan URL dan jadikan URL tersebut sebagai value untuk file input nya
    file_frame.on("select", function () {
      var attachment = file_frame.state().get("selection").first().toJSON();
      console.log(getFiletype(attachment.url));
      $(fileInputAttribute).val(attachment.url);
      previewPorcastFile();
    });

    // Open the upload dialog
    file_frame.open();
  });
});
