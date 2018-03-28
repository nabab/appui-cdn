
<bbn-table ref="cdn_management"
           :source="sourceTable"
           class="bbn-w-100"
           :info="true"
           :sortable="true"
           :editable="true"
           :pageable="true"
           editor="appui-cdn-management-library_edit"
           toolbar="appui-cdn-management-libraries_toolbar"
           :expander="$options.components['cdn-management-table-lib-versions']"
           :order="[{field: 'title', dir: 'ASC'}]"
>
  <bbn-column title="<?=_("Title")?>"
              field="title"
              :width="150"
              :sortable="false"
  ></bbn-column>

  <bbn-column title="<?=_("Folder name")?>"
              :width="150"
              field="name"
  ></bbn-column>

  <bbn-column title="<?=_("Function Name")?>"
              field="fname"
              :width="150"
  ></bbn-column>

  <bbn-column title="<?=_("Latest")?>"
              field="latest"
              :width="85"
  ></bbn-column>

  <bbn-column title="<?=_("Author")?>"
              field="author"
              :width="70"
              :render="showIconAuthor"
  ></bbn-column>
  <bbn-column title="<?=_("Licence")?>"
              field="licence"
              :width="70"
              :render="showIconLicense"
  ></bbn-column>
  <bbn-column title="<?=_("Web site")?>"
                field="website"
                :width="70"
                :render="showIconWeb"
  ></bbn-column>
  <bbn-column title="<?=_("Download")?>"
                field="download_link"
                :width="70"
                :render="showIconDownload"
  ></bbn-column>
  <bbn-column   title="<?=_("Documentation")?>"
                field="doc_link"
                :width="70"
                :render="showIconDoc"
  ></bbn-column>
  <bbn-column   title="<?=_("GitHub")?>"
                field="git"
                :width="70"
                :render="showIconGit"
  ></bbn-column>
  <bbn-column   title="<?=_("Support")?>"
                field="support_link"
                :width="70"
                :render="showIconSupportLink"
  ></bbn-column>
  <bbn-column  title="<?=_("Actions")?>"
              :buttons="buttons"
  ></bbn-column>
</bbn-table>

<script type="text/x-template" id="apst-cdn-management-table-lib-versions">
  <div style="height: 200px; position:relative" v-if="versionsInfo.length">
    <bbn-table :source="versionsInfo"
               :editable="true"
               editor="appui-cdn-management-library_edit"
               :info="true"
               :sortable="true"
               ref="tableVersionsLib"
               class="bbn-full-screen"
    >
      <bbn-column title="<?=_('Version')?>"
                  field="name"
      ></bbn-column>
      <bbn-column title="<?=_('Date')?>"
                  field="date_added"
      ></bbn-column>
      <bbn-column :width="130"
                  :tcomponent="$options.components.addVersions"
                  :buttons="buttonsVersion"
      ></bbn-column>
    </bbn-table>
  </div>
  <!--div class="bbn-w-100" v-else>
    <span class="bbn-c bbn-padded bbn-b">
      <?=_('NO VERSIONS')?>
    </span>
  </div-->
</script>
