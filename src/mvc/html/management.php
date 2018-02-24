
<bbn-table ref="management"
           :source="source.all_lib"
           :info="true"
           :sortable="true"
           :editable="true"
           :order="[{field: 'title', dir: 'DESC'}]"
>
  <bbn-column title="<?=_("Title")?>"
              field="title"
              :width="120"
              :sortable="false"
  ></bbn-column>

  <bbn-column title="<?=_("Folder name")?>"
              :width="100"
              field="name"
  ></bbn-column>

  <bbn-column title="<?=_("Function Name")?>"
              field="fname"
              :width="100"
  ></bbn-column>

  <bbn-column title="<?=_("Latest")?>"
              field="latest"
              :width="30"
  ></bbn-column>

  <bbn-column title="<?=_("Author")?>"
              field="author"
              :width="30"
              :render="showIconAuthor"
  ></bbn-column>
  <bbn-column title="<?=_("Licence")?>"
              field="licence"
              :width="30"
              :render="showIconLicense"
  ></bbn-column>
  <bbn-column title="<?=_("Web site")?>"
                field="website"
                :width="30"
                :render="showIconWeb"
  ></bbn-column>
  <bbn-column title="<?=_("Download")?>"
                field="download_link"
                :width="30"
                :render="showIconDownload"
  ></bbn-column>
  <bbn-column   title="<?=_("Documentation")?>"
                field="doc_link"
                :width="30"
                :render="showIconDoc"
  ></bbn-column>
  <bbn-column   title="<?=_("GitHub")?>"
                field="git"
                :width="30"
                :render="showIconGit"
  ></bbn-column>
  <bbn-column   title="<?=_("Support")?>"
                field="support_link"
                :width="30"
                :render="showIconSupportLink"
  ></bbn-column>
  <bbn-column :width="80"
              title="<?=_("Actions")?>"
              :buttons="buttons"
  ></bbn-column>
</bbn-table>
<script type="text/x-template" id="cdn-management-info-lib">
  <bbn-splitter orientation="horizontal">
    <bbn-pane :size="500">
      <div class="bbn-w-100 k-header bbn-middle">
        <strong>
          "<?=_("INFO")?>"
        </strong>
      </div>
      <bbn-table
        :title="'<?=_("Information")?>'"
        :source="infos"
        :columns="cols"
      >
      </bbn-table>
    </bbn-pane>
    <bbn-pane>
    </bbn-pane>
  </bbn-splitter>
</script>
