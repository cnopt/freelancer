// global dimensions and bounding boxes

const width = 950
let dimensions = {
  width: width,
  height: width * 0.24,
  margin: {
    top: 30,
    right: 10,
    bottom: 52,
    left: 120,
  },
}

dimensions.boundedWidth = dimensions.width
- dimensions.margin.left
- dimensions.margin.right
dimensions.boundedHeight = dimensions.height
- dimensions.margin.top
- dimensions.margin.bottom


async function drawTable() {
  // access data from json
  let dataset = await d3.json("./data/exp_level_years.json")

  const table = d3.select("#table")
  const numberOfRows= dataset.length
  const grayScale = d3.interpolateHcl("#fff","#aaa")

  // table columns
  const columns = [
    {label: "Years of Experience",  type: "text",   format: d => d.years_experience},
    {label: "Users",    type: "number", format: d => d.users}
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
      .attr("years-exp", d.years_experience)
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
        .style("background", column => column.background && column.background(d))
  })
}


async function drawYearsHistogram() {
  // read json file
  let dataset = await d3.json("./data/exp_level_years.json")
  const metricAccessor = d => +d.users // ensure datatype is numerical

  var svg = d3.select("#years-wrapper")
    .append("svg")
      .attr("class", "histogram-chart")
      .attr("width", dimensions.width)
      .attr("height", dimensions.height)
    .append("g")
      .attr("transform",
            "translate(" + dimensions.margin.left + "," + dimensions.margin.top + ")");

  svg.attr('width', window.innerWidth)

  // Add X axis
  var x = d3.scaleLinear()
    .domain([0, 250])
    .range([ 0, dimensions.boundedWidth])
  svg.append("g")
    .attr("transform", "translate(0," + dimensions.boundedHeight + ")")
    .attr("class", "xAxisInfo")
    .call(d3.axisBottom(x)
      .ticks(5)
      .tickSize(-dimensions.boundedHeight)
      .tickPadding(10))
  svg.append("text")             
  .attr("transform", "translate(" + dimensions.boundedWidth/2.25 + "," + dimensions.boundedHeight*1.37 + ")")
  .style("text-anchor", "middle")
  .attr("class", "xAxisLabel")
  .text("No. of users");
  svg.append("g")
  .attr("transform", "translate(0," + 3 + ")")
  .attr("class", "xAxisInfo")
  .call(d3.axisTop(x)
    .ticks(5)
    .tickSize(-dimensions.boundedHeight)
    .tickPadding(10))


  // Y axis
  var y = d3.scaleBand()
    .range([ 0, dimensions.boundedHeight])
    .domain(dataset.map(function(d) { return d.years_experience; }))
    .padding(0.3);
  svg.append("g")
    .attr("class", "yAxisInfo")
    .call(d3.axisLeft(y))

    
  // Bars
  svg.selectAll("myRect")
    .data(dataset)
    .enter()
      .append("rect")
      .attr("years-exp", d => d.years_experience)
      .attr("y", function(d) { return y(d.years_experience); })
      .attr("height", y.bandwidth())
      .attr("width", 0) // always equal to 0


  rect = svg.selectAll("rect")
  // animate the bars on page load 
  rect
    .transition()
        .duration(950)
        .ease(d3.easeQuadOut)
        .attr("width", function(d) { return x(d.users); })
      //  .delay(function(d,i) { return(i*60) })


  // tooltip for bars
  const tooltip = d3.select("#tooltip")

  svg.selectAll("rect")
    .on("mouseenter", barHoverEnter)
    .on("mouseleave", barHoverLeave)

  // bar hover functions to listen for mouseover and mouseleave events
  function barHoverEnter(data) {
    tooltip.style("opacity", 1)
    tooltip.select("#name")
      .text(data.years_experience)
    tooltip.select("#count")
      .text(data.users)
    tooltip.style("left", (d3.event.pageX - 200) + "px")
    tooltip.style("top", (d3.event.pageY - 285) + "px")
  }

  function barHoverLeave(data) {
    tooltip.style("opacity", 0)
  }         
}

async function drawSkillLevelHistogram() {

  let dataset = await d3.json("./data/exp_level_skill-level.json")
  const metricAccessor = d => +d.users // ensure data is numerical

  var svg = d3.select("#skill-level-wrapper")
    .append("svg")
      .attr("class", "histogram-chart")
      .attr("width", dimensions.width)
      .attr("height", dimensions.height)
    .append("g")
      .attr("transform",
            "translate(" + dimensions.margin.left + "," + dimensions.margin.top + ")");

  svg.attr('width', window.innerWidth)

  // Add X axis
  var x = d3.scaleLinear()
    .domain([0, 250])
    .range([ 0, dimensions.boundedWidth])
  svg.append("g")
    .attr("transform", "translate(0," + dimensions.boundedHeight + ")")
    .attr("class", "xAxisInfo")
    .call(d3.axisBottom(x)
      .ticks(5)
      .tickSize(-dimensions.boundedHeight)
      .tickPadding(10))
  svg.append("text")             
  .attr("transform", "translate(" + dimensions.boundedWidth/2.25 + "," + dimensions.boundedHeight*1.37 + ")")
  .style("text-anchor", "middle")
  .attr("class", "xAxisLabel")
  .text("No. of users");
  svg.append("g")
  .attr("transform", "translate(0," + 3 + ")")
  .attr("class", "xAxisInfo")
  .call(d3.axisTop(x)
    .ticks(5)
    .tickSize(-dimensions.boundedHeight)
    .tickPadding(10))


  // Y axis
  var y = d3.scaleBand()
    .range([ 0, dimensions.boundedHeight])
    .domain(dataset.map(function(d) { return d.skill_level; }))
    .padding(0.3);
  svg.append("g")
    .attr("class", "yAxisInfo")
    .call(d3.axisLeft(y))

    
  // Bars
  svg.selectAll("myRect")
    .data(dataset)
    .enter()
      .append("rect")
      .attr("years-exp", d => d.skill_level)
      .attr("y", function(d) { return y(d.skill_level); })
      .attr("height", y.bandwidth())
      .attr("width", 0) // always equal to 0


  rect = svg.selectAll("rect")

  // animate the bars 
  rect
    .transition()
        .duration(950)
        .ease(d3.easeQuadOut)
        .attr("width", function(d) { return x(d.users); })
      //  .delay(function(d,i) { return(i*60) })



  // tooltip for bars
  const tooltip = d3.select("#tooltip")

  svg.selectAll("rect")
    .on("mouseenter", barHoverEnter)
    .on("mouseleave", barHoverLeave)

  function barHoverEnter(data) {
    tooltip.style("opacity", 1)
    tooltip.select("#name")
      .text(data.skill_level)
    tooltip.select("#count")
      .text(data.users)
    tooltip.style("left", (d3.event.pageX - 200) + "px")
    tooltip.style("top", (d3.event.pageY - 285) + "px")
  }

  function barHoverLeave(data) {
    tooltip.style("opacity", 0)
  }         
}

async function drawCompletionTimeChart() {
  // generate spline chart using c3 d3 library
  var chart = c3.generate({
    bindto: '#c3-chart',
    size: {
      width: 950
    },
    color: {
      pattern: ["#12CBC4", "#4897e9", "#8e72cf", "#b14795"]
    },
    data: {
      json: [
        {year: 2015, Novice: 9, Intermediate: 7, Skilled: 3, Expert: 3},
        {year: 2016, Novice: 10, Intermediate: 6, Skilled: 4, Expert: 3},
        {year: 2017, Novice: 8, Intermediate: 5, Skilled: 3, Expert: 2},
        {year: 2018, Novice: 6, Intermediate: 4, Skilled: 3, Expert: 2},
        {year: 2019, Novice: 4, Intermediate: 3, Skilled: 2, Expert: 1}
      ],
      keys: {
        value: ['Novice', 'Intermediate', 'Skilled', 'Expert']
      }, // define the type for each group
      types: {
        novice: 'spline',
        intermediate: 'spline',
        skilled: 'spline',
        expert: 'spline'
      }
    },
    axis: {
      y: {
        label: {
          text: 'Avg. Job Completion Time (Days)',
          position: 'outer-middle'
        }
      },
      x: { // group by year
        type: 'category',
        categories: ['2015', '2016', '2017', '2018', '2019']
      }
    },
    tooltip: {
      format: {
        value: function (value) { return value + ' days' }
      }
    }
  });



}

// call functions
drawYearsHistogram()
drawSkillLevelHistogram()
drawCompletionTimeChart()
//drawTable()
