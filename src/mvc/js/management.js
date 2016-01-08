// CDN tabstrip
var cdnTabStrip = $("#pe49ajAssj3knvVvn323").kendoTabStrip({
    animation:  {
      open: {
        effects: "fadeIn"
      }
    },
    activate: function(a){
      $(this.element).redraw();
    }
  }).data("kendoTabStrip");

// Main TabStrip container padding fix
$("#pe49ajAssj3knvVvn323").closest("div.k-content").css("padding", 0);