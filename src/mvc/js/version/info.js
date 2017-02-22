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

  bbn.fn.popup(
    $("#kkk3jaSdh23490hqAsdha93").html(),
    kendo.format(data.lng.libraryVersion, dataItem.library, dataItem.name),
    800, false,
    function(cont){
      var kcont = cont.data("kendoWindow");

      // Set dynamic window's height
      cont.closest(".k-window").height("");

      // Bind version's info
      kendo.bind(cont, dataItem);

      // Version's files TreeView
      $("#daij444jasdjhi332jiosdajo").html(dataItem.files_tree);

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

    }
  );
};