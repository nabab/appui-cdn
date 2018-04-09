
<bbn-table ref="cdn_management"
           :source="sourceTable"
           class="bbn-w-100"
           :info="true"
           :sortable="true"
           :editable="true"
           :pageable="true"
           editor="appui-cdn-management-library_edit"
           toolbar="appui-cdn-management-toolbar"
           expander="appui-cdn-management-versions"
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
              cls="bbn-c"
              :width="85"
  ></bbn-column>

  <bbn-column title="<?=_("Author")?>"
              field="author"
              :width="70"
              :render="showIconAuthor"
              cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Licence")?>"
              field="licence"
              :width="70"
              :render="showIconLicense"
              cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Web site")?>"
                field="website"
                :width="70"
                :render="showIconWeb"
                cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Download")?>"
                field="download_link"
                :width="70"
                :render="showIconDownload"
                cls="bbn-c"
  ></bbn-column>
  <bbn-column   title="<?=_("Documentation")?>"
                field="doc_link"
                :width="70"
                :render="showIconDoc"
                cls="bbn-c"
  ></bbn-column>
  <bbn-column   title="<?=_("GitHub")?>"
                field="git"
                :width="70"
                :render="showIconGit"
                cls="bbn-c"
  ></bbn-column>
  <bbn-column   title="<?=_("Support")?>"
                field="support_link"
                :width="70"
                :render="showIconSupportLink"
                cls="bbn-c"
  ></bbn-column>
  <bbn-column cls="bbn-c"
              :buttons="buttons"
  ></bbn-column>
</bbn-table>
