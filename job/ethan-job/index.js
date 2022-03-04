$(document).ready(function() {
    $("div.apply-form-container").hide(); //hide application forms
    $(".open-apply-form").click(function() {
      // on click...
      $("div.apply-form-container").eq($(this).index(".open-apply-form")) //select correct form
        .toggle(); //and show/hide it
    })
});