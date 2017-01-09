/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 12:36
 */
var versionInfo = function(e, grid){
  var item = $(e.target).closest("tr[role=row]"),
      dataItem = grid.dataItem(item);

  // Date fix
  dataItem.date_added = moment(dataItem.date_added).format('DD/MM/YYYY');

  appui.fn.popup(
    $("#kkk3jaSdh23490hqAsdha93").html(),
    kendo.format(data.lng.libraryVersion, dataItem.library, dataItem.name),
    800, false,
    function(w){
      var cont = w,
          kcont = w.data("kendoWindow"),
          infoMasonry = function(){
            $("div.info-masonry-container", cont).masonry({
              itemSelector: ".info-masonry"
            });
          };

      // Set window's max height
      kcont.setOptions({maxHeight: appui.env.height - 50});

      // Set dynamic window's height
      cont.closest(".k-window").height("");

      // Bind version's info
      kendo.bind(cont, dataItem);

      // Version's files TreeView
      $("#daij444jasdjhi332jiosdajo").html(dataItem.files_tree);

      $("div.k-treeview", "#daij444jasdjhi332jiosdajo").data("kendoTreeView").bind("expand", function(){
        setTimeout(function(){
          infoMasonry();
        }, 300);
      });

      $("div.k-treeview", "#daij444jasdjhi332jiosdajo").data("kendoTreeView").bind("collapse", function(){
        setTimeout(function(){
          infoMasonry();
        }, 300);
      });

      // Version's dependencies list
      $("#isfih3huasdf92huf823hyhas93").html(function(){
        if ( dataItem.dependencies.length ){
          var ret = '<ul>';
          $.each(dataItem.dependencies, function(i,v){
            ret += '<li>' + v.title + ' - ' + v.version + '</li>';
          });
          ret += '</ul>';
          return ret;
        }
        return '<div style="text-align: center">' + data.lng.no_depend + '</div>';
      });

      // Version's slave dependencies list
      $("#snjgdnasdoi234nasdnu1opteoh").html(function(){
        if ( dataItem.slave_dependencies.length ){
          var ret = '<ul>';
          $.each(dataItem.slave_dependencies, function(i,v){
            ret += '<li>' + v.title + ' - ' + v.version + '</li>';
          });
          ret += '</ul>';
          return ret;
        }
        return '<div style="text-align: center">' + data.lng.no_depend + '</div>';
      });

      infoMasonry();
    }
  );
};