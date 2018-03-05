<bbn-table :source="source">
  <bbn-column title= "<?=_('Library')?>"
              field= "title"
  ></bbn-column>
  <bbn-column title= "<?=_('Local version')?>"
              field= "local"
              cls= "bbn-c"
  ></bbn-column>
  <bbn-column title= "<?=_('Latest version')?>"
              field= "latest"
              cls= "bbn-c w3-red"
  ></bbn-column>
  <bbn-column title= " "
              width= 110
              field= "title"
              :buttons= "buttons"
  ></bbn-column>
</bbn-table>
