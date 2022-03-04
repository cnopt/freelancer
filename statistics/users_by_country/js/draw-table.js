async function drawTable() {

  // read data from json file
  let dataset = await d3.json("./data/countries.json")
  dataset = dataset.sort((a, b) => d3.descending(a.users, b.users)) // sort data by descending

  const table = d3.select("#table-users-by-country")

  const numberOfRows= dataset.length
  const grayScale = d3.interpolateHcl("#fff","#aaa")
  const userScale = d3.scaleLinear()
                        .domain(d3.extent(dataset.slice(0, numberOfRows), d => d.users))
                        .range([0, 1])
  const colorScale = d3.interpolateHcl("#fff","#84a8e3")
  const percentScale = d3.scaleLinear()
                           .domain(d3.extent(dataset.slice(0, numberOfRows), d => d.percentage))
                           .range([0, 1])
  // table columns
  const columns = [
    {label: "Country",  type: "text",   format: d => d.country},
    {label: "Users",    type: "number", format: d => d.users,    background: d => grayScale(userScale(d.users))},
    {label: "Percentage", type: "number", format: d => d.percentage, background: d => colorScale(percentScale(d.percentage))}
  ]

  // table columns/header
  table.append("thead").append("tr")
    .selectAll("thead")
    .data(columns)
    .enter().append("th")
      .text(d => d.label)
      .attr("class", d => d.type)

  // actual table data
  const body = table.append("tbody")

  dataset.slice(0, numberOfRows).forEach(d => {
    body.append("tr")
      .attr("country", d.country)
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
      //  .style("background", column => column.background && column.background(d))
  })
}



async function drawHistogram() {

  let dataset = await d3.json("./countries.json")
  dataset = dataset.sort((a, b) => d3.descending(a.users, b.users)) // sort by descending

  const metricAccessor = d => +d.users
  
  const width = 950
  let dimensions = {
    width: width,
    height: width * 0.4,
    margin: {
      top: 10,
      right: 10,
      bottom: 40,
      left: 120,
    },
  }

  dimensions.boundedWidth = dimensions.width
  - dimensions.margin.left
  - dimensions.margin.right
  dimensions.boundedHeight = dimensions.height
  - dimensions.margin.top
  - dimensions.margin.bottom
  

  var svg = d3.select("#wrapper")
    .append("svg")
      .attr("width", dimensions.width)
      .attr("height", dimensions.height)
    .append("g")
      .attr("transform",
            "translate(" + dimensions.margin.left + "," + dimensions.margin.top + ")");

 // svg.attr('width', window.innerWidth)

  // Add X axis
  var x = d3.scaleLinear()
    .domain([0, 45])
    .range([ 0, dimensions.boundedWidth])
    .nice();
  svg.append("g")
    .attr("transform", "translate(0," + dimensions.boundedHeight + ")")
    .attr("class", "xAxisInfo")
    .call(d3.axisBottom(x))



  // Y axis
  var y = d3.scaleBand()
    .range([ 0, dimensions.boundedHeight])
    .domain(dataset.map(function(d) { return d.country; }))
    .padding(.1);
  svg.append("g")
    .attr("class", "yAxisInfo")
    .call(d3.axisLeft(y))


    
  // Bars
  svg.selectAll("myRect")
    .data(dataset)
    .enter()
      .append("rect")
      .attr("country", d => d.country)
      .attr("y", function(d) { return y(d.country); })
      .attr("height", y.bandwidth())
      .attr("width", 0) // always equal to 0


  rect = svg.selectAll("rect")

  // animate the bars 
  rect
    .transition()
        .attr("width", function(d) { return x(d.users); })
        .delay(function(d,i) { return(i*100) })



  // tooltip for bars
  const tooltip = d3.select("#tooltip")

  svg.selectAll("rect")
    .on("mouseenter", barHoverEnter)
    .on("mouseleave", barHoverLeave)

  function barHoverEnter(data) {
    tooltip.style("opacity", 1)
    tooltip.select("#count")
      .text(data.users)
    tooltip.select("#percent")
      .text(data.percentage)
    tooltip.style("left", (d3.event.pageX - 200) + "px")
    tooltip.style("top", (d3.event.pageY - 140) + "px")
  }

  function barHoverLeave(data) {
    tooltip.style("opacity", 0)
  }         
}



async function drawMap() {

    // The svg
  var svg = d3.select("#map-svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");

    // svg.attr("width", window.innerWidth)

  // Map and projection
  var path = d3.geoPath();
  var projection = d3.geoMercator()
    .scale(170)
    .center([0,25])
    .translate([width / 2, height / 2]);

  // Data and color scale
  var data = d3.map();
  const colorScale = d3.scaleLinear()
    .domain([1,50])
    .interpolate(d3.interpolateHcl)
    .range(["#9fe3e1", '#b53471']);

  // get geoJSON data for world map from URL in order to draw the map based on json data
  var worldmap = d3.json("https://raw.githubusercontent.com/holtzy/D3-graph-gallery/master/DATA/world.geojson")
  var countries = d3.csv("./data/countries_with_code.csv", function(d) { data.set(d.code, +d.users); })

  // legend
  var g = svg.append("g")
    .attr("class", "legendThreshold")
    .attr("transform", "translate(10,215)");
    g.append("text")
        .attr("class", "caption")
        .attr("x", 0)
        .attr("y", -6)
        .text("Users");
  var labels = ['0', '1-5', '6-10', '11-25', '26-35', '36-50'];
  var legend = d3.legendColor()
        .labels(function (d) { return labels[d.i]; })
        .shapePadding(7)
        .scale(colorScale);
    svg.select(".legendThreshold")
        .call(legend);
  

  Promise.all([worldmap, countries]).then(function(values) {
    // Draw the map
    svg.append("g")
      .selectAll("path")
      .data(values[0].features)
      .enter()
      .append("path")
        // draw each country
        .attr("d", d3.geoPath()
          .projection(projection)
        )
        // set the color of each country
        .attr("fill", function (d) {
          d.total = data.get(d.id) || 0;
          return colorScale(d.total);
        });
      })
}


async function drawUserSignupByCountry() {
  // generate step and area-step graphs using c3 d3 library
  var chart = c3.generate({
    bindto: '#chart-user-signup-by-country',
    size: {
      height:360,
      width:950
    },
    color: {
      pattern: ["#12CBC4", "#4897e9", "#8e72cf", "#b14795"]
    },
    data: {      
      x: "x",
          columns: [
            ["x", "2015", "2016", "2017", "2018", "2019"],
            ["Australia",9, 6, 8, 12, 12, 10, 19],
            ["Pakistan",8, 12, 12, 14, 11, 10, 18],
            ["Spain",4, 4, 12, 17, 15, 9, 10],
            ["Brazil",5, 5, 11, 7, 19, 10, 13],
            ["Belgium",5, 6, 5, 11, 9, 10, 11]
          ],
    types: {
      Australia: 'step',
      Pakistan: 'step',
      Spain: 'step',
      Brazil: 'step',
      Belgium: 'step'
      // 'line', 'spline', 'step', 'area', 'area-step'
  },
    },
    axis: {
      y: {
        label: {
          text: 'New Users Registered',
          position: 'outer-middle'
        }
      }
    },
    tooltip: {
      format: {
        value: function (value) { return value + ' new users' }
      }
    }
});
  // add ability to select which dataset is being shown on the graph
  // changing to a single country will change the dataset as well as its type to an area-step graph for better readability
  const selector = document.getElementById('country-selector')
  selector.addEventListener('change', selectorChangeEvent)

  function selectorChangeEvent() {
    // switch statement based on which country selected from dropdown
    switch (selector.value) {
        case 'All':
            chart.load({
                columns: [
                  ["Australia",9, 6, 8, 12, 12, 10, 19],
                  ["Pakistan",8, 12, 12, 14, 11, 10, 18],
                  ["Spain",4, 4, 12, 17, 15, 9, 10],
                  ["Brazil",5, 5, 11, 7, 19, 10, 13],
                  ["Belgium",5, 6, 5, 11, 9, 10, 11]
                ],
                types: {
                  Australia: 'step',
                  Pakistan: 'step',
                  Spain: 'step',
                  Brazil: 'step',
                  Belgium: 'step'
                },
                unload:["Australia","Pakistan", "Spain", "Brazil", "Belgium"]
          })
        break;
        case 'Australia':
            chart.load({
                columns: [
                  ["Australia",9, 6, 8, 12, 12, 10, 19],
                ],
                types: {
                  Australia: 'area-step'
                },
                unload:["Pakistan", "Spain", "Brazil", "Belgium"]
            });
        break;
        case 'Pakistan':
            chart.load({
                columns: [
                  ["Pakistan",8, 12, 12, 14, 11, 10, 18],
                ],
                types: {
                  Pakistan: 'area-step'
                },
                unload:["Australia", "Spain", "Brazil", "Belgium"]
            });
        break;
        case 'Spain':
            chart.load({
                columns: [
                  ["Spain",4, 4, 12, 17, 15, 9, 10],
                ],
                types: {
                  Spain: 'area-step'
                },
                unload:["Australia", "Pakistan", "Brazil", "Belgium"]
            });
        break;
        case 'Brazil':
            chart.load({
                columns: [
                  ["Brazil",5, 5, 11, 7, 19, 10, 13],
                ],
                types: {
                  Brazil: 'area-step'
                },
                unload:["Australia", "Pakistan", "Spain", "Belgium"]
            });
        break;
        case 'Belgium':
            chart.load({
                columns: [
                  ["Belgium",5, 6, 5, 11, 9, 10, 11]
                ],
                types: {
                  Belgium: 'area-step'
                },
                unload:["Australia", "Pakistan", "Spain", "Brazil"]
            });
        break;

    }
  }

}

// call functions
drawTable()
drawMap()
drawUserSignupByCountry()
