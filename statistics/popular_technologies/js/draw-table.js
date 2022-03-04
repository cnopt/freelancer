
async function drawHeaderBubbleChart() {

  // define dataset from within the chart
  const dataset = {
    "children": [
        {"Name":"Angular","Count":431},
        {"Name":"JavaScript","Count":415},
        {"Name":"Machine Learning","Count":258},
        {"Name":"Smart Home","Count":207},
        {"Name":"AI","Count":189},
        {"Name":"React","Count":180},
        {"Name":"PHP","Count":171},
        {"Name":"SQL","Count":163},
        {"Name":"Excel","Count":156},
        {"Name":"Unit Testing","Count":151},
        {"Name":"C++","Count":148},
        {"Name":"Typescript","Count":148},
        {"Name":"Automation","Count":142},
        {"Name":"Alexa","Count":137},
        {"Name":"Data Analytics","Count":134},
        {"Name":"C#","Count":133},
        {"Name":"Voice Recognition","Count":132},
        {"Name":"Image Recognition","Count":130},
        {"Name":"HTML","Count":129},
        {"Name":"CSS","Count":201},
        {"Name":"Embedded Systems","Count":105},
        {"Name":"3D Graphics","Count":134},
        {"Name":"Animation","Count":173}
      ]
};

  const diameter = 800
                
  const color = d3.scaleLinear()
                  .domain([0,18])
                  .range(["#12CBC4", "#B53471"])
                  .interpolate(d3.interpolateHcl)

  const bubble = d3.pack(dataset)
      .size([diameter, diameter])
      .padding(2);

  const svg = d3.select("#general-wrapper")
      .append("svg")
      .attr("width", 970)
      .attr("height", diameter)
      .attr("class", "bubble-svg")

  const nodes = d3.hierarchy(dataset)
      .sum(function(d) { return d.Count; });

  const node = svg.selectAll(".bubble")
      .data(bubble(nodes).descendants())
      .enter()
      .filter(function(d){
          return  !d.children
      })
      .append("g")
      .attr("class", "bubble")
      .attr("transform", function(d) {
          return "translate(" + d.x*1.3 + "," + d.y*0.8 + ")";
      });

  node.append("title")
      .text(function(d) {
          return d.data.Name + ": " + d.data.Count;
      });

  node.append("circle")
      .attr("r", function(d) {
          return d.r;
      })
      .style("fill", function(d,i) {
          return color(i);
      })
      // mouse function listeners
      .on("mouseenter", onMouseEnter)
      .on("mouseleave", onMouseLeave)

  // append labels to bubbles
  node.append("text")
      .attr("dy", "-0.2em")
      .attr("class", "bubble-name")
      .style("text-anchor", "middle")
      .text(function(d) {
          return d.data.Name.substring(0, d.r / 3);
      })
      .attr("font-size", function(d){
          return d.r/5;
      })
      .attr("fill", "white");

  // append associated value inside bubble
  node.append("text")
      .attr("dy", "1.2em")
      .attr("class", "bubble-value")
      .style("text-anchor", "middle")
      .text(function(d) {
          return d.data.Count;
      })
      .attr("font-size", function(d){
          return d.r/5;
      })
      .attr("fill", "white");

  d3.select(self.frameElement)
      .style("height", diameter + "px");

  var circleDivs = document.getElementsByClassName('bubble');

  // mouse hover functions to lower/raise bubble opacity
  function onMouseEnter(datum) {
    this.classList.add('active')
    this.parentNode.classList.add('active');
  }
  function onMouseLeave(datum) {
    this.classList.remove('active')
    this.parentNode.classList.remove('active');
  }


}

async function drawGroupedBubbleChart() {
    var w = 1000, h = 400;
    
    var radius = 4; // circle size
    var color = d3.scaleOrdinal(d3.schemeCategory20);
    var centerScale = d3.scalePoint().padding(1.5).range([0, w]);
    var forceStrength = 0.1; // 'gravity' force
    
    // add svg to html wrapper
    var svg = d3.select("#grouped-wrapper").append("svg")
      .attr("width", w)
      .attr("height", h)

    var simulation = d3.forceSimulation()
            .force("collide",d3.forceCollide( function(d){
              	return d.r + 8 }).iterations(16) 
            )
            .force("charge", d3.forceManyBody())
            .force("y", d3.forceY().y(h / 2))
            .force("x", d3.forceX().x(w / 2))
    
    // read the data from csv file
    d3.csv("./data/pop-tech-bubble.csv", function(data){
      data.forEach(function(d){
        d.r = radius;
        d.x = w / 2;
        d.y = h / 2;
      })
      
      // append circles to dom
      var circles = svg.selectAll("circle")
      	.data(data, function(d){ return d.ID ;});
      
      var circlesEnter = circles.enter().append("circle")
      	.attr("r", function(d, i){ return d.r; })
        .attr("cx", function(d, i){ return 175 + 25 * i + 2 * i ** 2; })
				.attr("cy", function(d, i){ return 250; })
      	.style("fill", function(d, i){ return color(d.ID); })
      	.style("stroke", function(d, i){ return color(d.ID); })
      	.style("stroke-width", 10)
      	.style("pointer-events", "all")
      	.call(d3.drag()
                .on("start", dragstarted)
                .on("drag", dragged)
                .on("end", dragended));
    
      circles = circles.merge(circlesEnter)
      
      function ticked() {
        circles
            .attr("cx", function(d){ return d.x; })
            .attr("cy", function(d){ return d.y; });
      }   

      simulation
            .nodes(data)
            .on("tick", ticked);
      
      // drag bubble event listener
      function dragstarted(d,i) {
        if (!d3.event.active) simulation.alpha(1).restart();
        d.fx = d.x;
        d.fy = d.y;
      }

      function dragged(d,i) {
        d.fx = d3.event.x;
        d.fy = d3.event.y;
      }

      // return bubble to expected position
      function dragended(d,i) {
        if (!d3.event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
      } 
      
      function splitBubbles(byVar) {
        
        centerScale.domain(data.map(function(d){ return d[byVar]; }));
        
        if(byVar == "all"){
          hideTitles()
        } else {
	        showTitles(byVar, centerScale);
        }
        
        // reset the 'x' force to draw the bubbles to their year centers
        simulation.force('x', d3.forceX().strength(forceStrength).x(function(d){ 
        	return centerScale(d[byVar]);
        }));

        simulation.alpha(2).restart();
      }
      
      function hideTitles() {
        svg.selectAll('.title').remove();
      }

      function showTitles(byVar, scale) {
       	var titles = svg.selectAll('.title')
          .data(scale.domain());
        
        titles.enter()
            .append('text')
          	.attr('class', 'title')
        	.merge(titles)
            .attr('x', function (d) { return 1.08*scale(d)-50; })
            .attr('y', 80)
            .attr('text-anchor', 'middle')
            .text(function (d) { return d; });
        
        titles.exit().remove() 
      }
      
      function setupButtons() {
        d3.select('#Technology').classed('active', true);

        d3.selectAll('.button')
          .on('click', function () {
          	
            // Remove active class from all buttons
            d3.selectAll('.button').classed('active', false);

            var button = d3.select(this);
            button.classed('active', true);
            var buttonId = button.attr('id');

            splitBubbles(buttonId);
          });
      }
      
      setupButtons()
      // by default sort by technology
      splitBubbles('Technology')
      
    })
    
}

// call functions
drawHeaderBubbleChart()
drawGroupedBubbleChart()
