(function(){
  /**
   * Created by BBN Solutions.
   * User: Mirko Argentino
   * Date: 15/12/2016
   * Time: 11:43
   */
  var libraryVersions = function (d){
    var versionsGrid = $("<div/>").appendTo(d.detailCell).kendoGrid({
      dataSource: {
        transport: {
          read: function (o){
            bbn.fn.post('cdn/data/versions', {id_lib: d.data.name}, function (p){
              if ( p.data ){
                o.success(p.data);
              }
            });
          },
          update: function (o){
            if ( o.data.id !== undefined ){
              var files = [];
              $("div", "#joisfd8723hifwe78238hds").each(function (i, v){
                files.push($(v).attr('path'));
              });
              bbn.fn.post('cdn/actions/version/edit', {
                id_ver: o.data.id,
                files: JSON.stringify(files),
                languages: JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data()),
                themes: JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data()),
                dependencies: $("#732ijfasASdha92389yasdh9823").data("kendoGrid").dataSource.data().toJSON(),
                latest: $("#hw4o5923noasd890324yho:checked").length
              }, function (d){
                if ( d.data.success ){
                  versionsGrid.data("kendoGrid").dataSource.read();
                  o.success();
                }
                else{
                  o.error();
                }
              });
            }
          },
          destroy: function (o){
            if ( (o.data.id !== undefined) && (o.data.library !== undefined) ){
              bbn.fn.post('cdn/actions/version/delete', {
                id_ver: o.data.id,
                library: o.data.library
              }, function (p){
                if ( p.data.success ){
                  if ( o.data.is_latest ){
                    var uidRow = d.data.uid;
                    d.data.set('latest', p.data.latest);
                    librariesGrid.data("kendoGrid").expandRow("tr[data-uid=" + uidRow + "]");
                  }
                  o.success();
                }
                else{
                  o.error();
                }
              });
            }
          }
        },
        schema: {
          model: {
            id: "id",
            fields: {
              id: {type: "numer", editable: false},
              name: {type: "string"},
              library: {type: "string"},
              content: {type: "string"},
              date_added: {type: "date"}
            }
          }
        }
      },
      columns: [{
        title: data.lng.version,
        field: 'name'
      }, {
        title: data.lng.date,
        field: 'date_added',
        template: function (e){
          return kendo.toString(e.date_added, "dd/MM/yyyy");
        }
      }, {
        title: '',
        width: 120,
        headerTemplate: '<a href="javascript:;" class="k-button k-button-icontext k-grid-add fa fa-plus add-ver"></a>',
        headerAttributes: {
          style: "text-align: center"
        },
        command: [{
          name: "info",
          template: '<a class="k-button k-grid-d-info fa fa-info" href="javascript:;"></a>'
        }, {
          name: "edit",
          text: {
            edit: data.lng.mod,
            update: data.lng.save,
            cancel: data.lng.cancel,
          },
          template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
        }, {
          name: "destroy",
          text: data.lng.del,
          template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
        }]
      }],
      dataBound: function (db){
        // Library's version info popup
        $("a.k-grid-d-info", versionsGrid).on("click", function (e){
          versionInfo(e, db.sender);
        });
        // Insert new library's version (Plus button)
        $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-ver", versionsGrid).on("click", function (e){
          bbn.fn.post('cdn/data/version/add', {folder: d.data.name}, function (p){
            versionAdd(p, d);
          });
        });
      },
      editable: {
        mode: "popup",
        confirmation: data.lng.delete_this_entry,
        window: {
          title: data.lng.edit_library,
          width: bbn.env.width - 100,
          maxHeight: bbn.env.height - 150
        }
      },
      edit: function (e){
        versionEdit(e);
      }
    });
  };
})();