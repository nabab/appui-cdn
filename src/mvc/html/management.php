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
  <bbns-column title="<?=_("Title")?>"
              field="title"
              :width="250"
              :sortable="false"
  ></bbns-column>

  <bbns-column title="<?=_("Folder name")?>"
              :width="200"
              field="name"
  ></bbns-column>

  <bbns-column title="<?=_("Function Name")?>"
              field="fname"
              :width="200"
  ></bbns-column>

  <bbns-column title="<?=_("Latest")?>"
              field="latest"
              cls="bbn-c"
              :width="100"
  ></bbns-column>
  <bbns-column title="<?=_('Infos')?>"
               :render="showInfos"
               cls="bbn-c"
  ></bbns-column>
  <bbns-column title="<?=_("Author")?>"
               field="author"
               :width="70"
               :render="showIconAuthor"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("Licence")?>"
               field="licence"
               :width="70"
               :render="showIconLicense"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("Web site")?>"
               field="website"
               :width="70"
               :render="showIconWeb"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("Download")?>"
               field="download_link"
               :width="110"
               :render="showIconDownload"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("Documentation")?>"
               field="doc_link"
               :width="110"
               :render="showIconDoc"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("GitHub")?>"
               field="git"
               :width="70"
               :render="showIconGit"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column title="<?=_("Support")?>"
               field="support_link"
               :width="70"
               :render="showIconSupportLink"
               cls="bbn-c"
               :hidden="true"
  ></bbns-column>
  <bbns-column cls="bbn-c buttonTable"
               :buttons="buttons"
               :width="160"
  ></bbns-column>
</bbn-table>
