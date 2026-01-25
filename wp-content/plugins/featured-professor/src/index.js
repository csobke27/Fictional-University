import "./index.scss"
import {useBlockProps} from "@wordpress/block-editor"
import { useSelect } from "@wordpress/data"
import { useState, useEffect} from "react"
import apiFetch from "@wordpress/api-fetch"

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: { type: "string" }
  },
  edit: EditComponent,
  save: function () {
    return null
  },
  example: {
        attributes: {
            profId: ""
        }
    }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("");

  useEffect(() => {
    if(props.attributes.profId){
      updateTheMeta();
      async function go(){
        const response = await apiFetch({ path: `/featuredProfessor/v1/getHTML/?profId=${props.attributes.profId}`, method: "GET" });
        setThePreview(response);
      }
      go();
    } else {
      setThePreview("");
      updateTheMeta();
    }
  }, [props.attributes.profId]);

  useEffect(() => {
    return () => {
      updateTheMeta();
    };
  }, []);

  function updateTheMeta() {
    const profsForMeta = wp.data.select("core/block-editor").getBlocks()
    .filter(block => block.name == "ourplugin/featured-professor")
    .map(block => block.attributes.profId)
    .filter((x, index, array) => {
      return array.indexOf(x) === index;
    })
    console.log(profsForMeta);
    wp.data.dispatch("core/editor").editPost({ meta: {featuredprofessor: profsForMeta} });
  }
  const blockProps = useBlockProps({className: "featured-professor-wrapper"});
  const allProfs = useSelect((select) => {
    return select("core").getEntityRecords("postType", "professor", { per_page: -1 })
  });
  if(allProfs === null) {
    return (
      <div {...blockProps}>
        <p>Loading professors...</p>
      </div>
    );
  }
  return (
    <div {...blockProps}>
      <div className="professor-select-container">
        <select onChange={e => props.setAttributes({ profId: e.target.value })} value={props.attributes.profId}>
          <option value="">Select a Professor</option>
          {allProfs.map(prof => {
            return (
            <option key={prof.id} value={prof.id} selected={props.attributes.profId === prof.id}>{prof.title.rendered}</option>
          )})}
        </select>
      </div>
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  )
}