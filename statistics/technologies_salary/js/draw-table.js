
async function drawSalaryLanguagesChart() {
  // load data from csv
  d3.csv("./data/technologies_languages_salarybracket.csv").then(d => chart(d))

  function chart(csv) {
  
    var keys = csv.columns.slice(2);
    var frameworks = [...new Set(csv.map(d => d.Language))]
  
    var svg = d3.select("#chart-programming-languages"),
      margin = {top: 35, left: 35, bottom: 0, right: 0},
      width = +svg.attr("width") - margin.left - margin.right,
      height = +svg.attr("height") - margin.top - margin.bottom;
  
    var x = d3.scaleBand()
      .range([margin.left, width - margin.right])
      .padding(0.1)
  
    var y = d3.scaleLinear()
      .rangeRound([height - margin.bottom, margin.top])
    
    // axes
    var xAxis = svg.append("g")
      .attr("transform", `translate(0,${height - margin.bottom})`)
      .attr("class", "x-axis")
  
    var yAxis = svg.append("g")
      .attr("transform", `translate(${margin.left},0)`)
      .attr("class", "y-axis")
  
    var data = csv.filter(f => f.Group)

    data.forEach(function(d) {
      d.total = d3.sum(keys, k => +d[k])
      return d
    })

    y.domain([0, d3.max(data, d => d3.sum(keys, k => +d[k]))])
      .nice();

    svg.selectAll(".y-axis")
      .call(d3.axisLeft(y)
      .ticks(null, "s"))

    x.domain(data.map(d => d.Language));

    svg.selectAll(".x-axis")
      .call(d3.axisBottom(x)
      .tickSizeOuter(0))

    var group = svg.selectAll("g.layer")
      .data(d3.stack()
      .keys(keys)(data), d => d.key)

    group.exit().remove()

    // purple colour scale for this bar
    const purpleColorScale = d3.scaleOrdinal()
      .range(["#0e0b14", "#1c1629", "#382d52", "#55447c", "#715ba5", "#8e72cf",])
      .domain(keys);

    // apply fill based on colour scale
    group.enter().append("g")
      .classed("layer", true)
      .attr("fill", d => purpleColorScale(d.key));

    var bars = svg.selectAll("g.layer").selectAll("rect")
      .data(d => d, e => e.data.Language);

    bars.exit().remove()

    // assign width based on scale of chart, assign height based on number
    bars.enter().append("rect")
      .attr("width", x.bandwidth())
      .merge(bars)
        .attr("x", d => x(d.data.Language))
        .attr("y", d => y(d[1]))
        .attr("height", d => y(d[0]) - y(d[1]))
        .transition()
        .attr("opacity", 1)


    var text = svg.selectAll(".text")
      .data(data, d => d.Language);

    text.exit().remove()

    text.enter().append("text")
      .attr("class", "text")
      .attr("text-anchor", "middle")
      .attr('class', 'barUsers')
      .merge(text)
        .attr("x", d => x(d.Language) + x.bandwidth() / 2)
        .attr("y", d => y(d.total) - 5)
        .text(d => d.total + ' users')

    // tooltip for bars
    const tooltip = d3.select("#tooltip")

      // legend
    var g = svg.append("g")
    .attr("class", "legendThreshold")
    .attr("transform", "translate(255,3)");
    g.append("text")
        .attr("class", "caption")
        .attr("x", 0)
        .attr("y", -6)
    var labels = ['0-10k', '10-20k', '20-30k', '30-40k', '40-50k', '50k+'];
    var legend = d3.legendColor()
        .labels(function (d) { return labels[d.i]; })
        .shapePadding(22)
        .shapeWidth(43)
        .shapeHeight(15)
        .orient('horizontal')
        .scale(purpleColorScale);
    svg.select(".legendThreshold")
        .call(legend);

  }
}

// draw the respective table that the above chart is pulling from so raw values are displayed neatly
async function drawSalaryLanguagesTable() {

  let dataset = await d3.csv("./data/technologies_languages_salarybracket.csv")
  const table = d3.select("#table-programming-languages")

  const numberOfRows= dataset.length

  // generate columns for table
  const columns = [
    {label: "Framework",  type: "text",   format: d => d.Language},
    {label: "0-10k",      type: "number", format: d => d.bracket1},
    {label: "10-20k",     type: "number", format: d => d.bracket2},
    {label: "20-30k",     type: "number", format: d => d.bracket3},
    {label: "30-40k",     type: "number", format: d => d.bracket4},
    {label: "40-50k",     type: "number", format: d => d.bracket5},
    {label: "50k+",       type: "number", format: d => d.bracket6}
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
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
          .style("background", column => column.background && column.background(d))
  })
}

// same chart layout as the above, however this time pulling from the javascript csv file
async function drawSalaryJSChart() {

  d3.csv("./data/technologies_js_frameworks_salarybracket.csv").then(d => chart(d))

  function chart(csv) {
  
    var keys = csv.columns.slice(2);
    var frameworks = [...new Set(csv.map(d => d.Framework))]
  
    var svg = d3.select("#chart-salary-js-frameworks"),
      margin = {top: 35, left: 35, bottom: 0, right: 0},
      width = +svg.attr("width") - margin.left - margin.right,
      height = +svg.attr("height") - margin.top - margin.bottom;
  
    var x = d3.scaleBand()
      .range([margin.left, width - margin.right])
      .padding(0.1)
  
    var y = d3.scaleLinear()
      .rangeRound([height - margin.bottom, margin.top])
  
    var xAxis = svg.append("g")
      .attr("transform", `translate(0,${height - margin.bottom})`)
      .attr("class", "x-axis")
  
    var yAxis = svg.append("g")
      .attr("transform", `translate(${margin.left},0)`)
      .attr("class", "y-axis")
  
    var data = csv.filter(f => f.Group)

    data.forEach(function(d) {
      d.total = d3.sum(keys, k => +d[k])
      return d
    })

    y.domain([0, d3.max(data, d => d3.sum(keys, k => +d[k]))])
      .nice();

    svg.selectAll(".y-axis")
      .call(d3.axisLeft(y)
      .ticks(null, "s"))

    x.domain(data.map(d => d.Framework));

    svg.selectAll(".x-axis")
      .call(d3.axisBottom(x)
      .tickSizeOuter(0))

    var group = svg.selectAll("g.layer")
      .data(d3.stack()
      .keys(keys)(data), d => d.key)

    group.exit().remove()

    const lightBlueColorScale = d3.scaleOrdinal()
      .range(["#011413", "#032827", "#07514e", "#0a7975", "#0ea29c", "#12cbc4",])
      .domain(keys);

    group.enter().append("g")
      .classed("layer", true)
      .attr("fill", d => lightBlueColorScale(d.key));

    var bars = svg.selectAll("g.layer").selectAll("rect")
      .data(d => d, e => e.data.Framework);

    bars.exit().remove()

    bars.enter().append("rect")
      .attr("width", x.bandwidth())
      .merge(bars)
        .attr("x", d => x(d.data.Framework))
        .attr("y", d => y(d[1]))
        .attr("height", d => y(d[0]) - y(d[1]))
        .transition()
        .attr("opacity", 1)


    var text = svg.selectAll(".text")
      .data(data, d => d.Framework);

    text.exit().remove()

    text.enter().append("text")
      .attr("class", "text")
      .attr("text-anchor", "middle")
      .attr('class', 'barUsers')
      .merge(text)
        .attr("x", d => x(d.Framework) + x.bandwidth() / 2)
        .attr("y", d => y(d.total) - 5)
        .text(d => d.total + ' users')

    // tooltip for bars
    const tooltip = d3.select("#tooltip")

      // legend
    var g = svg.append("g")
    .attr("class", "legendThreshold")
    .attr("transform", "translate(255,3)");
    g.append("text")
        .attr("class", "caption")
        .attr("x", 0)
        .attr("y", -6)
    var labels = ['0-10k', '10-20k', '20-30k', '30-40k', '40-50k', '50k+'];
    var legend = d3.legendColor()
        .labels(function (d) { return labels[d.i]; })
        .shapePadding(22)
        .shapeWidth(43)
        .shapeHeight(15)
        .orient('horizontal')
        .scale(lightBlueColorScale);
    svg.select(".legendThreshold")
        .call(legend);

  }
}

// similarly, draw the respective table below
async function drawSalaryJSTable() {

  let dataset = await d3.csv("./data/technologies_js_frameworks_salarybracket.csv")
  const table = d3.select("#table-salary-js-frameworks")

  const numberOfRows= dataset.length

  const columns = [
    {label: "Framework",  type: "text",   format: d => d.Framework},
    {label: "0-10k",      type: "number", format: d => d.bracket1},
    {label: "10-20k",     type: "number", format: d => d.bracket2},
    {label: "20-30k",     type: "number", format: d => d.bracket3},
    {label: "30-40k",     type: "number", format: d => d.bracket4},
    {label: "40-50k",     type: "number", format: d => d.bracket5},
    {label: "50k+",       type: "number", format: d => d.bracket6}
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
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
          .style("background", column => column.background && column.background(d))
  })
}

async function drawSalaryBackendChart() {

  d3.csv("./data/technologies_backend_salarybracket.csv").then(d => chart(d))

  function chart(csv) {
  
    var keys = csv.columns.slice(2);
    var frameworks = [...new Set(csv.map(d => d.Framework))]
  
    var svg = d3.select("#chart-backend-frameworks"),
      margin = {top: 35, left: 35, bottom: 0, right: 0},
      width = +svg.attr("width") - margin.left - margin.right,
      height = +svg.attr("height") - margin.top - margin.bottom;
  
    var x = d3.scaleBand()
      .range([margin.left, width - margin.right])
      .padding(0.1)
  
    var y = d3.scaleLinear()
      .rangeRound([height - margin.bottom, margin.top])
  
    var xAxis = svg.append("g")
      .attr("transform", `translate(0,${height - margin.bottom})`)
      .attr("class", "x-axis")
  
    var yAxis = svg.append("g")
      .attr("transform", `translate(${margin.left},0)`)
      .attr("class", "y-axis")
  
    const blueColorScale = d3.scaleOrdinal()
    .range(["#001017", "#00212e", "#00425d", "#00648b", "#0085ba", "#00a7e9",])
    .domain(keys);
  
    var data = csv.filter(f => f.Group)

    data.forEach(function(d) {
      d.total = d3.sum(keys, k => +d[k])
      return d
    })

    y.domain([0, d3.max(data, d => d3.sum(keys, k => +d[k]))])
      .nice();

    svg.selectAll(".y-axis")
      .call(d3.axisLeft(y)
      .ticks(null, "s"))

    x.domain(data.map(d => d.Framework));

    svg.selectAll(".x-axis")
      .call(d3.axisBottom(x)
      .tickSizeOuter(0))

    var group = svg.selectAll("g.layer")
      .data(d3.stack()
      .keys(keys)(data), d => d.key)

    group.exit().remove()

    group.enter().append("g")
      .classed("layer", true)
      .attr("fill", d => blueColorScale(d.key));

    var bars = svg.selectAll("g.layer").selectAll("rect")
      .data(d => d, e => e.data.Framework);

    bars.exit().remove()

    bars.enter().append("rect")
      .attr("width", x.bandwidth())
      .merge(bars)
        .attr("x", d => x(d.data.Framework))
        .attr("y", d => y(d[1]))
        .attr("height", d => y(d[0]) - y(d[1]))
        .transition()
        .attr("opacity", 1)


    var text = svg.selectAll(".text")
      .data(data, d => d.Framework);

    text.exit().remove()

    text.enter().append("text")
      .attr("class", "text")
      .attr("text-anchor", "middle")
      .attr('class', 'barUsers')
      .merge(text)
        .attr("x", d => x(d.Framework) + x.bandwidth() / 2)
        .attr("y", d => y(d.total) - 5)
        .text(d => d.total + ' users')

    // tooltip for bars
    const tooltip = d3.select("#tooltip")

      // legend
    var g = svg.append("g")
    .attr("class", "legendThreshold")
    .attr("transform", "translate(255,3)");
    g.append("text")
        .attr("class", "caption")
        .attr("x", 0)
        .attr("y", -6)
    var labels = ['0-10k', '10-20k', '20-30k', '30-40k', '40-50k', '50k+'];
    var legend = d3.legendColor()
        .labels(function (d) { return labels[d.i]; })
        .shapePadding(22)
        .shapeWidth(43)
        .shapeHeight(15)
        .orient('horizontal')
        .scale(blueColorScale);
    svg.select(".legendThreshold")
        .call(legend);

  }
}

async function drawSalaryBackendTable() {

  let dataset = await d3.csv("./data/technologies_backend_salarybracket.csv")
  const table = d3.select("#table-backend-frameworks")

  const numberOfRows= dataset.length

  const columns = [
    {label: "Framework",  type: "text",   format: d => d.Framework},
    {label: "0-10k",      type: "number", format: d => d.bracket1},
    {label: "10-20k",     type: "number", format: d => d.bracket2},
    {label: "20-30k",     type: "number", format: d => d.bracket3},
    {label: "30-40k",     type: "number", format: d => d.bracket4},
    {label: "40-50k",     type: "number", format: d => d.bracket5},
    {label: "50k+",       type: "number", format: d => d.bracket6}
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
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
          .style("background", column => column.background && column.background(d))
  })
}

async function drawSalaryMLChart() {

  d3.csv("./data/technologies_machine_learning_salarybracket.csv").then(d => chart(d))

  function chart(csv) {
  
    var keys = csv.columns.slice(2);
    var frameworks = [...new Set(csv.map(d => d.Framework))]
  
    var svg = d3.select("#chart-machine-learning-frameworks"),
      margin = {top: 35, left: 35, bottom: 0, right: 0},
      width = +svg.attr("width") - margin.left - margin.right,
      height = +svg.attr("height") - margin.top - margin.bottom;
  
    var x = d3.scaleBand()
      .range([margin.left, width - margin.right])
      .padding(0.1)
  
    var y = d3.scaleLinear()
      .rangeRound([height - margin.bottom, margin.top])
  
    var xAxis = svg.append("g")
      .attr("transform", `translate(0,${height - margin.bottom})`)
      .attr("class", "x-axis")
  
    var yAxis = svg.append("g")
      .attr("transform", `translate(${margin.left},0)`)
      .attr("class", "y-axis")
  
    var data = csv.filter(f => f.Group)

    data.forEach(function(d) {
      d.total = d3.sum(keys, k => +d[k])
      return d
    })

    y.domain([0, d3.max(data, d => d3.sum(keys, k => +d[k]))])
      .nice();

    svg.selectAll(".y-axis")
      .call(d3.axisLeft(y)
      .ticks(null, "s"))

    x.domain(data.map(d => d.Framework));

    svg.selectAll(".x-axis")
      .call(d3.axisBottom(x)
      .tickSizeOuter(0))

    var group = svg.selectAll("g.layer")
      .data(d3.stack()
      .keys(keys)(data), d => d.key)

    group.exit().remove()

    const redColorScale = d3.scaleOrdinal()
      .range(["#12050b", "#240a16", "#48142d", "#6c1f43", "#90295a", "#b53471",])
      .domain(keys);

    group.enter().append("g")
      .classed("layer", true)
      .attr("fill", d => redColorScale(d.key));

    var bars = svg.selectAll("g.layer").selectAll("rect")
      .data(d => d, e => e.data.Framework);

    bars.exit().remove()

    bars.enter().append("rect")
      .attr("width", x.bandwidth())
      .merge(bars)
        .attr("x", d => x(d.data.Framework))
        .attr("y", d => y(d[1]))
        .attr("height", d => y(d[0]) - y(d[1]))
        .transition()
        .attr("opacity", 1)


    var text = svg.selectAll(".text")
      .data(data, d => d.Framework);

    text.exit().remove()

    text.enter().append("text")
      .attr("class", "text")
      .attr("text-anchor", "middle")
      .attr('class', 'barUsers')
      .merge(text)
        .attr("x", d => x(d.Framework) + x.bandwidth() / 2)
        .attr("y", d => y(d.total) - 5)
        .text(d => d.total + ' users')

    // tooltip for bars
    const tooltip = d3.select("#tooltip")

      // legend
    var g = svg.append("g")
    .attr("class", "legendThreshold")
    .attr("transform", "translate(255,3)");
    g.append("text")
        .attr("class", "caption")
        .attr("x", 0)
        .attr("y", -6)
    var labels = ['0-10k', '10-20k', '20-30k', '30-40k', '40-50k', '50k+'];
    var legend = d3.legendColor()
        .labels(function (d) { return labels[d.i]; })
        .shapePadding(22)
        .shapeWidth(43)
        .shapeHeight(15)
        .orient('horizontal')
        .scale(redColorScale);
    svg.select(".legendThreshold")
        .call(legend);

  }
}

async function drawSalaryMLTable() {

  let dataset = await d3.csv("./data/technologies_machine_learning_salarybracket.csv")
  const table = d3.select("#table-machine-learning-frameworks")

  const numberOfRows= dataset.length

  const columns = [
    {label: "Framework",  type: "text",   format: d => d.Framework},
    {label: "0-10k",      type: "number", format: d => d.bracket1},
    {label: "10-20k",     type: "number", format: d => d.bracket2},
    {label: "20-30k",     type: "number", format: d => d.bracket3},
    {label: "30-40k",     type: "number", format: d => d.bracket4},
    {label: "40-50k",     type: "number", format: d => d.bracket5},
    {label: "50k+",       type: "number", format: d => d.bracket6}
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
      .selectAll("td")
      .data(columns)
      .enter()
        .append("td")
          .text(column => column.format(d))
          .attr("class", column => column.type)
          .style("background", column => column.background && column.background(d))
  })
}

// call functions
drawSalaryJSChart()
drawSalaryJSTable()
drawSalaryBackendChart()
drawSalaryBackendTable()
drawSalaryMLChart()
drawSalaryMLTable()
drawSalaryLanguagesChart()
drawSalaryLanguagesTable()