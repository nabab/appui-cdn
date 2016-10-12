// CDN tabstrip
var isActivated = false,
    cdnTabStrip = $("#pe49ajAssj3knvVvn323").kendoTabStrip({
      animation:  {
        open: {
          effects: "fadeIn"
        }
      },
      activate: function(a){
        if ( !isActivated ){
          configurationsGridInit();
          librariesGridInit();
          isActivated = true;
        }
        $(this.element).redraw();
      }
    }).data("kendoTabStrip");
appui.fn.log(ele, data);
cdnTabStrip.trigger("activate");

// Main TabStrip container padding fix
$("#pe49ajAssj3knvVvn323").closest("div.k-content").css("padding", 0);