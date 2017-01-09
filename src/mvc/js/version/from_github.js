/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 21/12/2016
 * Time: 19:47
 */
var fromGitHub = function(url, libData){
  if ( url && libData && libData.data && libData.data.name ){
    appui.fn.post('cdn/github/versions', {url: url}, function(d){
      if ( d.data && d.data.versions && d.data.git_user && d.data.git_repo ){
        appui.fn.popup($("#ioasd8923hiasdu9021jio3rhwfe8998a").html(), data.lng.githubVersion, function(w){
          d.data.folder = libData.data.name;
          kendo.bind(w, d.data);
          $("select[name=git_id_ver]", w).kendoDropDownList({
            dataSource: d.data.versions,
            dataTextField: 'text',
            dataValueField: 'id',
            value: appui.fn.get_field(d.data.versions, 'is_latest', true, 'id')
          });
          $("form", w).data("script", function(p){
            if ( p.data ){
              appui.fn.closePopup();
              versionAdd(p, libData);
            }
          });
        });
      }
    });
  }
};