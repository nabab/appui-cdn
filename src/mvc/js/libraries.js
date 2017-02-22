// All libraries grid
var librariesGridInit = function(){
  var librariesGrid = $("#RRsj983Jfjnv2kasihj234"),
      updatesBt;

  librariesGrid.kendoGrid({
    dataSource: {
      transport: {
        read: function(o){
          if ( data.all_lib ){
            o.success(data.all_lib);
          }
        },
        create: function(o){
          var files = [];
          $("div", "#joisfd8723hifwe78238hds").each(function(i,v){
            files.push($(v).attr('path'));
          });
          o.data.vname = $("#u93248safn328dasuq89yu").val();
          o.data.files = JSON.stringify(files);
          o.data.languages = JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data());
          o.data.themes = JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data());
          o.data.dependencies = $("#732ijfasASdha92389yasdh9823").data("kendoGrid").dataSource.data().toJSON();
          o.data.latest = 1;
          bbn.fn.post('cdn/actions/library/add', o.data, function(d){
            if ( d.data && d.data.length ){
              librariesGrid.data("kendoGrid").dataSource.data(d.data);
              o.success();
            }
            else {
              o.error();
            }
          });
        },
        update: function(o){
          o.data.edit = 1;
          o.data.new_name = o.data.name;
          o.data.name = $("input[name=old_name]:hidden", bbn.fn.get_popup()).val();
          bbn.fn.post('cdn/actions/library/edit', o.data, function(d){
            if ( d.data ){
              o.success(d.data);
            }
            else {
              o.error();
            }
          });
        },
        destroy: function(o){
          if ( o.data.name !== undefined ){
            bbn.fn.post('cdn/actions/library/delete', {name: o.data.name}, function(d){
              if ( d.data.success ){
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
        model :{
          id: "name",
          fields: {
            title: { type: "string" },
            name: { type: "string" },
            fname: { type: "string" },
            description: { type: "string" },
            //latest: { type: "string"},
            website: { type: "string" },
            author: { type: "string" },
            licence: { type: "string" },
            download_link: { type: "string" },
            doc_link: { type: "string" },
            git: { type: "string" },
            support_link: { type: "string" },
            vname: { type: "string" },
            files: { type: "string" },
            languages: { type: "string" },
            themes: { type: "string" },
            dependencies: { type: "string" }
          }
        }
      }
    },
    columns: [{
      title: data.lng.title,
      field: 'title'
    }, {
      title: data.lng.folderName,
      field: 'name'
    }, {
      title: data.lng.functionName,
      field: 'fname'
    }, {
      title: data.lng.latest,
      field: 'latest'
    }, {
      title: data.lng.author,
      field: 'author',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.author && e.author.length ? '<i class="fa fa-user"></i>' : '';
      }
    }, {
      title: data.lng.licence,
      field: 'licence',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.licence && e.licence.length ? '<i class="fa fa-copyright"></i>' : '';
      }
    }, {
      title: data.lng.webSite,
      field: 'website',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.website && e.website.length ? '<a href="' + e.website + '" target="_blank"><i class="fa fa-globe"></i></a>' : '';
      }
    }, {
      title: data.lng.download,
      field: 'download_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.download_link && e.download_link.length ? '<a href="' + e.download_link + '" target="_blank"><i class="fa fa-download"></i></a>' : '';
      }
    }, {
      title: data.lng.doc,
      field: 'doc_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.doc_link && e.doc_link.length ? '<a href="' + e.doc_link + '" target="_blank"><i class="fa' +
        ' fa-book"></i></a>' : '';
      }
    }, {
      title: data.lng.gitHub,
      field: 'git',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.git && e.git.length ? '<a href="' + e.git + '" target="_blank"><i class="fa fa-github"></i></a>' : '';
      }
    }, {
      title: data.lng.supp,
      field: 'support_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.support_link && e.support_link.length ? '<a href="' + e.support_link + '" target="_blank"><i class="fa' +
        ' fa-ambulance"></i></a>' : '';
      }
    }, {
      title: '',
      width: 120,
      command: [{
        name: "info",
        template: '<a class="k-button k-grid-info fa fa-info" href="javascript:;"></a>'
      }, {
        name: "edit",
        text: {
          edit: data.lng.mod,
          update: data.lng.save,
          cancel: data.lng.cancel
        },
        template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
      }, {
        name: 'destroy',
        text: data.lng.del,
        template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
      }]
    }],
    toolbar: $("#kasdnjnsdfguiwerhasdh").html(),
    editable: {
      mode: "popup",
      confirmation: data.lng.delete_this_entry,
      window: {
        width: bbn.env.width - 150,
        maxHeight: bbn.env.height - 50,
      },
      template: function(e){
        if ( !e.name ){
          return $("#iertlkasdoih234a9sdjl12eiasd").html();
        }
        return $("#asioajfpasdhua89324hio38w9asdjlk").html();
      }
    },
    edit: function(e){
      var cont = e.container,
          kcont = cont.data("kendoWindow");

      // Set the right title to window
      cont.prev().find(".k-window-title:first").html(e.model.name ? data.lng.editLib : data.lng.new_library);

      kcont.bind('activate', function(){
        // Resize and center
        bbn.fn.analyzeContent(cont, true);
        $("div.appui-form-label", cont).css('padding-right', '0');
        kcont.center();
      });

      // Insert mode
      if ( !e.model.name ){
        libraryAdd(e);
      }
      else {
        libraryEdit(e);
      }

    },
    // Library's versions subgrid
    detailInit: function(d){
      libraryVersions(d);
    },
    dataBound: function(){
      $("a.k-grid-info", librariesGrid).on("click", function(e){
        var grid = librariesGrid.data("kendoGrid"),
            dataItem = grid.dataItem($(e.target).closest("tr.k-master-row"));
        bbn.fn.popup(
          $("#i3h34uefn94uh3rnfe9sfd23u").html(),
          data.lng.library + ': '  + dataItem.title,
          600, false,
          function(w){
            var cont = w,
               kcont = w.data("kendoWindow");

            //bbn.fn.analyzeContent(w, true);
            bbn.fn.redraw(w, true);

            // Set window's max height
            kcont.setOptions({maxHeight: bbn.env.height - 50});

            // Set the right licence name
            if ( dataItem.licence ){
              dataItem.licence = bbn.fn.get_field(data.licences, 'licence', dataItem.licence, 'name');
            }

            // Bind library's info
            kendo.bind(w, dataItem);
            // Library's versions grid
            bbn.fn.post('cdn/data/versions', {id_lib: dataItem.name}, function(p){
              if ( p.data !== undefined ){
                $("#hufsa93hias9n38fn3293h389r2", cont).kendoGrid({
                  dataSource: p.data,
                  dataBound: function(){
                    kcont.trigger("resize");
                  },
                  columns: [{
                    title: data.lng.name,
                    field: 'name'
                  }, {
                    title: data.lng.date,
                    field: 'date_added',
                    template: function(t){
                      return moment(t.date_added).format('DD/MM/YYYY')
                    }
                  }]
                });
              }
            }
          );
        });
      });
    }
  });

  updatesBt = $("#anksd8u23hasdh09oi234h8", librariesGrid).kendoButton({
    enable: false,
    click: function(){
      bbn.fn.popup('<div></div>', '<i class="fa fa-github"></i> ' + data.lng.githubUpdates, bbn.env.width-100, false, function(w){

        $("div", w).first().kendoGrid({
          dataSource: updatesBt.data("updates"),
          columns: [{
            title: 'Library',
            field: 'title'
          }, {
            title: 'Local version',
            field: 'local'
          }, {
            title: 'Latest version',
            field: 'latest'
          }, {
            title: '',
            width: 50,
            template: '<a class="k-button fa fa-trash" href="javascript:;"></a>'
          }],
          dataBound: function(e){
            $("a.k-button.fa-trash", w).click(function(c){
              var dataItem = e.sender.dataItem($(c.target).closest("tr"));
              e.sender.dataSource.remove(dataItem);
              updatesBt.data("updates").splice(bbn.fn.search(updatesBt.data("updates"), 'title', dataItem.title), 1);
              w.data("kendoWindow").center();
              updatesBt.html(
                '<i class="fa fa-cubes" style="margin-right: 5px"></i>' +
                data.lng.updates +
                (updatesBt.data("updates").length ?
                  ' (<span style="color: green">' + updatesBt.data("updates").length + '</span>)' :
                  '')
              );
              if ( !updatesBt.data("updates").length ){
                updatesBt.data("kendoButton").enable(false);
              }
            });
            w.data("kendoWindow").center();
          }
        });
      });
    }
  });

  $("#pqwenksdfh823rsnasdh98", ele).kendoButton({
    click: function(){
      bbn.fn.confirm(data.lng.checkUpdates, function(){
        bbn.fn.post('cdn/github/updates', {}, function(d){
          if ( d.data && d.data.length ){
            updatesBt.data("updates", d.data);
            updatesBt.data("kendoButton").enable(true);
            updatesBt.html(
              '<i class="fa fa-cubes" style="margin-right: 5px"></i>' +
              data.lng.updates +
              ' (<span style="color: green">' + d.data.length + '</span>)'
            );
          }
        });
      });
    }
  });

  // Search field
  $("#F4LLL9jdn3nhasS38sh301dfs", ele).kendoAutoComplete({
    placeholder: data.lng.search_library +'...'
  });

};
