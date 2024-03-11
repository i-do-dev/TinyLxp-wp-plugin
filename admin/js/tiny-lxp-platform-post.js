var TinyLXPPlatformText = '';
var TinyLXPPlatformProps = null;
var currentSectionState = "create";
var currentsectionId = 0;

(function (wp) {
  var TinyLXPPlatformIcon = wp.element.createElement(wp.primitives.SVG, {
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 24 24"
  }, wp.element.createElement(wp.primitives.Path, {
    d: "M6 14H4V6h2V4H2v12h4M7.1 17h2.1l3.7-14h-2.1M14 4v2h2v8h-2v2h4V4"
  }));

  var TinyLXPPlatformButton = function (props) {
    return wp.element.createElement(
      wp.blockEditor.RichTextToolbarButton, {
      icon: TinyLXPPlatformIcon,
      title: 'Tiny LXP tool',
      onClick: function () {
        if (typeof props.value.start === 'undefined') {
          props.value.start = props.value.text.length;
          props.value.end = props.value.text.length;
        }
        TinyLXPPlatformText = '';
        if (props.value.end > props.value.start) {
          TinyLXPPlatformText = props.value.text.substr(props.value.start, props.value.end - props.value.start);
        }
        TinyLXPPlatformProps = props;
        jQuery('.tiny-lxp-platform-modal').addClass("active");
      },
    }
    );
  }
  wp.richText.registerFormatType(
    'lti-platform-format/insert-tool', {
    title: 'Tiny LXP tool',
    tagName: 'ltiplatformtool',
    className: null,
    edit: TinyLXPPlatformButton,
  }
  );
})(window.wp);

(function ($) {
  $(document).ready(function () {
    var totalChips = $("#option-chips").attr('tota-chips');

    function deeplink() {
      var urlParams = new URLSearchParams(window.location.search);
      window.open('../?lti-platform&deeplink&post=' + encodeURIComponent(urlParams.get('post')) + '&tool=' + encodeURIComponent($("input[name='tool']:checked").val()), '_blank', 'width=1000,height=800');
      $('.tiny-lxp-platform-modal').removeClass('active');
    }

    $.get('../?lti-platform&tools', function (response) {
      $('#wpwrap').append(response);
      $('.tiny-lxp-platform-tool').on('change', function () {
        $('#tiny-lxp-platform-select').prop('disabled', false);
      });

      $('#tiny-lxp-platform-select').on('click', function () {
        $.get('../?lti-platform&usecontentitem&tool=' + encodeURIComponent($("input[name='tool']:checked").val()), function (response) {
          if (response.useContentItem) {
            deeplink();
          } else {
            if (!window.TinyLXPPlatformText) {
              window.TinyLXPPlatformText = $('input[name="tool"]:checked').attr('toolname');
            }
            if ($("#preview_lit_connections").length) {
              deeplink();
            }
            var id = Math.random().toString(16).substr(2, 8);
            window.TinyLXPPlatformProps.onChange(window.wp.richText.insert(window.TinyLXPPlatformProps.value, '[lti-platform tool=' + $("input[name='tool']:checked").val() + ' id=' + id + ']' + window.TinyLXPPlatformText + '[/lti-platform]'));
            window.TinyLXPPlatformProps.onFocus();
            $('.tiny-lxp-platform-modal').removeClass('active');
          }
        });
      });

      $('#tiny-lxp-platform-cancel').on('click', function () {
        $('.tiny-lxp-platform-modal').removeClass('active');
        $('#postdivrich').addClass('wp-editor-expand');
      });

      $('#preview_lit_connections').on('click', function () {
        jQuery('.tiny-lxp-platform-modal').addClass("active");
        $('#postdivrich').removeClass('wp-editor-expand');
      });

      $(".tool-input-tr").on('click', function () {
        $(this).find('td input[type=radio]').prop('checked', true);
        $('#tiny-lxp-platform-select').prop("disabled", false);
      });

      $(".course_remove_lesson").on('click', function () {
        var deletedLessons = $("#course_removed_lessons").val();
        $(this).parent().fadeOut();
        if (deletedLessons) {
          $("#course_removed_lessons").val(deletedLessons + "," + $(this).attr('lesson_id'));
        } else {
          $("#course_removed_lessons").val($(this).attr('lesson_id'));
        }
      });

      $('body').on('click', '.chip-close', function () {
        if (confirm("Are you sure you want to remove?") == true) {
          var sectionId = $(this).parent('div').attr('identifier');
          var host = window.location.origin + '/wp-json/lms/v1/delete/trek/section';
          jQuery.ajax({
            type: "post",
            dataType: "json",
            url: host,
            data: { section_id: sectionId },
            success: function (response) {
              if (window.currentSectionState == "edit" && window.currentsectionId == sectionId) {
                var url = window.location.href;
                if (url.indexOf("&action=edit") >= 0) {
                  $('#playlist-select-area').css("display", "inline");
                }
                $('#section-title').text("Add New Section");
                $('#btnSaveSection').text("Create");
                CKEDITOR.instances['ck-editor-id'].setData('');
                $('#option-title-select-box').val('');
                window.currentSectionState = "create";
                window.currentsectionId = 0;
              }
              $("[identifier=" + sectionId + "]").remove();
              appendCoursePlaylistSelectOptions();
            }
          });
        }
      });

      appendCoursePlaylistSelectOptions();
      function appendCoursePlaylistSelectOptions(selctedOption = null) {
        var options = '';
        var courseId = $('#course_select_options').find(":selected").val();
        var postID = $('#post_ID').val();
        var host = window.location.origin + '/wp-json/lms/v1/get/playlists';
        jQuery.ajax({
          type: "get",
          dataType: "json",
          url: host,
          data: { course_id: courseId, post_id: postID },
          success: function (response) {
            if (response.length == 0 && selctedOption == null) {
              options += '<option> No Section Available </option>';
            } else {
              options += '<option>---Select Section---</option>';
            }
            for (var j = 0, len = response.length; j < len; ++j) {
              options += '<option value="' + response[j] + '">' + response[j] + '</option>';
            }
            if (selctedOption != null) {
              options += selctedOption;
            }

            $("#option-title-select-box").html(options);
          }
        });
      }

      $('body').on('click', '#btnSaveStudentSection', function () {
        var content = CKEDITOR.instances['student-section-editor'].getData();
        var postID = $('#post_ID').val();
        // post content to server
        jQuery.ajax({
          type: "post",
          dataType: "json",
          url: window.location.origin + ajaxurl,
          data: { action: 'trek_student_section', content: content, post_id: postID },
          success: function (response) {
            console.log('response >>>>>>>>>> ', response);
            if (response.status == 200) {
              alert("Section saved successfully");
            }
          }
        });
      });

      $('body').on('click', '#btnSaveSection', function () {
        
        var title = $('#option-title-select-box').val();

        if (title.indexOf("No Section Available") >= 0 || title.indexOf("---Select Section---") >= 0) {
          alert("No section selected");
          return;
        }
        var content = CKEDITOR.instances['ck-editor-id'].getData();
        var sort = $('#trek_sort').val();
        var postID = $('#post_ID').val();
        var host = window.location.origin + '/wp-json/lms/v1/store/trek/section';
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').removeClass("active-edit-trek-option");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').removeClass("active-chip-close");
        $("[identifier=" + window.currentsectionId + "]").removeClass("edit-playlist-chip");
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').css("visibility", "visible");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').css("visibility", "visible");
        jQuery.ajax({
          type: "post",
          dataType: "json",
          url: host,
          data: { title: title, content: content, post_id: postID, section_id: window.currentsectionId, sort },
          success: function (recordId) {
            if (recordId == 0) {
              alert('Please enter post "Title" and "Description" first.');
            } else {
              appendCoursePlaylistSelectOptions();
              if (window.currentSectionState == "edit") {
                $('#chip-title-' + window.currentsectionId).text();
              } else {
                $("#option-chips").append('<div class="playlist-chip" identifier="' + recordId + '">  <span id="chip-title-' + recordId + '"> ' + title + ' </span>  <span class="edit-trek-options"><span style="margin-top:5px" class="dashicons dashicons-edit"></span> </span> <span type="button" class="chip-close"><span style="margin-top:5px" class="dashicons dashicons-no"></span> </span> </div>');
              }
              $('#playlist-select-area').css("display", "inline");
              window.currentsectionId = 0;
              window.currentSectionState = "create";
              CKEDITOR.instances['ck-editor-id'].setData('');
              $('#option-title-select-box').val('');
              $('#section-title').text("Add New Section");
              $('#btnSaveSection').text("Create");
              $('#chips-alternate').text("");
              $('#btnCancelUpdate').css("display", "none");
              $('#trek_sort').val(0);
              location.reload();
            }
          }
        });
      });
      $('body').on('click', '#btnCancelUpdate', function () {
        appendCoursePlaylistSelectOptions();
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').removeClass("active-edit-trek-option");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').removeClass("active-chip-close");
        $("[identifier=" + window.currentsectionId + "]").removeClass("edit-playlist-chip");
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').css("visibility", "visible");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').css("visibility", "visible");
        window.currentsectionId = 0;
        window.currentSectionState = "create";
        CKEDITOR.instances['ck-editor-id'].setData('');
        $('#option-title-select-box').val('');
        $('#section-title').text("Add New Section");
        $('#btnSaveSection').text("Create");
        $('#chips-alternate').text("");
        $('#btnCancelUpdate').css("display", "none");
        $('#playlist-select-area').css("display", "inline");
        $('#trek_sort').val(0);

      });
      $('body').on('click', '.edit-trek-options', function () {
        var url = window.location.href;
        $('#playlist-select-area').css("display", "none");
        window.currentSectionState = "edit";
        $('#btnSaveSection').text("Update");
        $('#btnCancelUpdate').css("display", "inline-block");
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').removeClass("active-edit-trek-option");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').removeClass("active-chip-close");
        $("[identifier=" + window.currentsectionId + "]").removeClass("edit-playlist-chip");
        $("[identifier=" + window.currentsectionId + "]").find('.edit-trek-options').css("visibility", "visible");
        $("[identifier=" + window.currentsectionId + "]").find('.chip-close').css("visibility", "visible");
        var sectionId = $(this).parent('div').attr('identifier');
        // $("[identifier=" + sectionId + "]").find('.edit-trek-options').addClass("active-edit-trek-option");
        // $("[identifier=" + sectionId + "]").find('.chip-close').addClass("active-chip-close");
        $("[identifier=" + sectionId + "]").find('.edit-trek-options').css("visibility", "hidden");
        $("[identifier=" + sectionId + "]").find('.chip-close').css("visibility", "hidden");
        $("[identifier=" + sectionId + "]").addClass("edit-playlist-chip");
        window.currentsectionId = sectionId;
        var host = window.location.origin + '/wp-json/lms/v1/get/trek/section';
        jQuery.ajax({
          type: "get",
          dataType: "json",
          url: host,
          data: { section_id: sectionId },
          success: function (response) {
            $('#section-title').text("Edit \"" + response[0].title + "\" Section");
            $('#trek_sort').val(parseInt(response[0].sort));
            CKEDITOR.instances['ck-editor-id'].setData(response[0].content);
            if ($("#option-title-select-box option[value='" + response[0].title + "']").length == 0) {
              option = '<option selected value="' + response[0].title + '">' + response[0].title + '</option>';
              appendCoursePlaylistSelectOptions(option);
            }
            if (response.length > 0) {
              $("#option-title-select-box").val(response[0].title.trim());
            }
          }
        });
      });

      $("#course_select_options").on('change', function () {
        appendCoursePlaylistSelectOptions();
      });


      $('body').on('click', '#school_remove_lxp_user', function () {
        if (confirm("Are you sure you want to remove?") == true) {
          var userId = $(this).attr('lxp_user_id');
          var host = window.location.origin + '/wp-json/lms/v1/delete/school/lxp/user';
          jQuery.ajax({
            type: "post",
            dataType: "json",
            url: host,
            data: { user_id: userId },
            success: function (response) {
            }
          });
          $(this).parent().fadeOut();
        }
      });
    });

  });
})(jQuery);
