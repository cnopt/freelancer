  // global dimenions and bounding boxes
  const width = 950
  let dimensions = {
    width: width,
    height: width * 0.4,
    margin: {
      top: 0,
      right: 52,
      bottom: 40,
      left: 55,
    },
  }

  dimensions.boundedWidth = dimensions.width
  - dimensions.margin.left
  - dimensions.margin.right
  dimensions.boundedHeight = dimensions.height
  - dimensions.margin.top
  - dimensions.margin.bottom


async function drawTimeOfYearChart() {

    // assign svg to sit inside the HTML wrapper
    var svg = d3.select("#job-listings-by-year-wrapper")
      .append("svg")
        .attr("id", "chart-job-listings-by-year")
        .attr("width", dimensions.width)
        .attr("height", dimensions.height)
      .append("g")
        .attr("transform",
              "translate(" + dimensions.margin.left + "," + dimensions.margin.top + ")");
    
    // Labels of row and columns
    var myGroups = ["2015","2016","2017","2018","2019"]
    var myVars = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
    
    // Build X scales and axis:
    var x = d3.scaleBand()
      .range([ 0, dimensions.boundedWidth ])
      .domain(myGroups)
      .padding(0.01);
    svg.append("g")
      .attr("transform", "translate(0," + dimensions.boundedHeight*1.025 + ")")
      .call(d3.axisBottom(x))
      
    
    // Build X scales and axis:
    var y = d3.scaleBand()
      .range([ dimensions.boundedHeight, 0 ])
      .domain(myVars)
      .padding(0.1);
    svg.append("g")
      .attr("transform", "translate(-10,0)")
      .call(d3.axisLeft(y));
    
    // Build color scale
    var blueColorScale = d3.scaleLinear()
      .range(["white", "#0a7975"])
      .domain([1,100])

        // legend
        var g = svg.append("g")
        .attr("class", "legendThreshold")
        .attr("transform", "translate(305,3)")
        var defs = g.append("defs")
        var linearGrad = defs.append("linearGradient")
            .attr("id", "grad1")
            .attr("x1", "0%")
            .attr("y1", "100%")
            .attr("x2", "0%")
            .attr("y2", "0%")
            linearGrad.append("stop")
                .attr("offset", "0%")
                .style("stop-color", "#fff" )
                .style("stop-opacity", "1")
            linearGrad.append("stop")
                .attr("offset", "100%")
                .style("stop-color", function() { return blueColorScale.range()[1] })
                .style("stop-opacity", "1")
        var rect = g.append("rect")
            .attr("class", "chart-job-postings-legend")
            .attr("x", 550)
            .attr("y", 0)
            .style("fill", "url(#grad1")
        var border = g.append("rect")
            .attr("class", "chart-job-postings-legend-border")
            .attr("x", 550)
            .attr("y", -1)
            .style("fill", "none")
            .style("stroke", "black")
            .style("stroke-width", "1px")
        var lesslabel = g.append("text")
            .attr("class", "text")
            .attr('class', 'chart-job-postings-legend-label')
            .style("text-anchor", "end")
            .attr("dx", "-12.5em")
            .attr("dy", "46em")
            .attr("transform", "rotate(-90)")
            .text("LESS ACTIVITY")
        var morelabel = g.append("text")
            .attr("class", "text")
            .attr('class', 'chart-job-postings-legend-label')
            .style("text-anchor", "end")
            .attr("dx", "0.1em")
            .attr("dy", "46em")
            .attr("transform", "rotate(-90)")
            .text("MORE ACTIVITY")
    
    // Read the data
    d3.csv("./data/jobs_by_year.csv", function(data) {
      svg.selectAll()
          .data(data, function(d) {return d.group+':'+d.variable;})
          .enter()
            .append("rect")
            .attr("x", function(d) { return x(d.group) })
            .attr("y", function(d) { return y(d.variable) })
            .attr("width", x.bandwidth() )
            .attr("height", y.bandwidth() )
            .style("fill", function(d) { return blueColorScale(d.value)} )

            // tooltip for bars
            const tooltip = d3.select("#tooltip")

            svg.selectAll("rect")
                .on("mouseenter", barHoverEnter)
                .on("mouseleave", barHoverLeave)

            function barHoverEnter(data) {
                tooltip.style("opacity", 1)
                tooltip.select("#count")
                    .text(data.value)
                tooltip.style("left", (d3.event.pageX - 60) + "px")
                tooltip.style("top", (d3.event.pageY - 60) + "px")
            }

            function barHoverLeave(data) {
                tooltip.style("opacity", 0)
            }    
    
    })

}

async function drawTimeOfDayChart() {
    // assign svg to sit inside html wrapper
    var svg = d3.select("#job-listings-by-day-wrapper")
    .append("svg")
      .attr("id", "chart-job-listings-by-day")
      .attr("width", dimensions.width)
      .attr("height", dimensions.height)
    .append("g")
      .attr("transform",
            "translate(" + dimensions.margin.left + "," + dimensions.margin.top + ")");
    
    // Labels of row and columns
    var myGroups = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
    var myVars = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
    
    // Build X scales and axis:
    var x = d3.scaleBand()
      .range([ 0, dimensions.boundedWidth ])
      .domain(myGroups)
      .padding(0.02);
    svg.append("g")
      .attr("transform", "translate(0," + dimensions.boundedHeight*1.025 + ")")
      .call(d3.axisBottom(x))
      
    
    // Build X scales and axis:
    var y = d3.scaleBand()
      .range([ dimensions.boundedHeight, 0 ])
      .domain(myVars)
      .padding(0.02);
    svg.append("g")
      .attr("transform", "translate(-10,0)")
      .call(d3.axisLeft(y));
    
    // Build color scale
    var blueColorScale = d3.scaleLinear()
      .range(["white", "#0a7975"])
      .domain([1,100])

    // legend
    var g = svg.append("g")
        .attr("class", "legendThreshold")
        .attr("transform", "translate(305,3)")
        var defs = g.append("defs")
        var linearGrad = defs.append("linearGradient")
            .attr("id", "grad1")
            .attr("x1", "0%")
            .attr("y1", "100%")
            .attr("x2", "0%")
            .attr("y2", "0%")
            linearGrad.append("stop")
                .attr("offset", "0%")
                .style("stop-color", "#fff" )
                .style("stop-opacity", "1")
            linearGrad.append("stop")
                .attr("offset", "100%")
                .style("stop-color", function() { return blueColorScale.range()[1] })
                .style("stop-opacity", "1")
        var rect = g.append("rect")
            .attr("class", "chart-job-postings-legend")
            .attr("x", 550)
            .attr("y", 0)
            .style("fill", "url(#grad1")
        var border = g.append("rect")
            .attr("class", "chart-job-postings-legend-border")
            .attr("x", 550)
            .attr("y", -1)
            .style("fill", "none")
            .style("stroke", "black")
            .style("stroke-width", "1px")
        var lesslabel = g.append("text")
            .attr("class", "text")
            .attr('class', 'chart-job-postings-legend-label')
            .style("text-anchor", "end")
            .attr("dx", "-12.5em")
            .attr("dy", "46em")
            .attr("transform", "rotate(-90)")
            .text("LESS ACTIVITY")
        var morelabel = g.append("text")
            .attr("class", "text")
            .attr('class', 'chart-job-postings-legend-label')
            .style("text-anchor", "end")
            .attr("dx", "0.1em")
            .attr("dy", "46em")
            .attr("transform", "rotate(-90)")
            .text("MORE ACTIVITY")
        

    
    //Read the data
    d3.csv("./data/jobs_by_day.csv", function(data) {
      svg.selectAll()
          .data(data, function(d) {return d.group+':'+d.variable;})
          .enter()
            .append("rect")
            .attr("x", function(d) { return x(d.group) })
            .attr("y", function(d) { return y(d.variable) })
            .attr("width", x.bandwidth() )
            .attr("height", y.bandwidth() )
            .style("fill", function(d) { return blueColorScale(d.value)} )

            
            // tooltip for bars
            const tooltip = d3.select("#tooltip")

            svg.selectAll("rect")
                .on("mouseenter", barHoverEnter)
                .on("mouseleave", barHoverLeave)

            function barHoverEnter(data) {
                tooltip.style("opacity", 1)
                tooltip.select("#count")
                    .text(data.value)
                tooltip.style("left", (d3.event.pageX - 60) + "px")
                tooltip.style("top", (d3.event.pageY - 60) + "px")
            }

            function barHoverLeave(data) {
                tooltip.style("opacity", 0)
            }    
    
    })

}

async function drawPopularTechBubble() {
    // generate bubble chart using billboard d3 library
    var chart = bb.generate({
      size: {
        width: 950,
        height:450
      },
        color: {
            pattern: ["#12CBC4", "#4897e9", "#8e72cf", "#b14795", "#b53471"]
          },
        data: {
          columns: [
          ["JavaScript", 128, 100, 275, 203, 280],
          ["C++", 230, 247, 185, 107, 101],
          ["Machine Learning", 10, 68, 179, 289, 232],
          ["Ruby", 0, 9, 21, 24, 30],
          ["PHP", 107, 98, 70, 69, 50]
          ],
          type: "bubble",
          labels: false
        },
        bubble: {
          maxR: 40
        },
        axis: {
          x: {
            type: "category",
            categories: [
                "2015",
                "2016",
                "2017",
                "2018",
                "2019"
              ]
          },
          y: {
            max: 400,
            label: {
                text: 'Number of job postings',
                position: 'outer-middle'
              }
          }
        },
        bindto: "#chart-job-listings-popular-tech"
      });
      return;
};

async function drawRadar() {
  // generate radar chart using bibblboard d3 library
    var chart = bb.generate({
        size: {
            height: 430,
            width: 950
        },
        data: {
          x: "x",
          columns: [
            ["x", "0-50 USD", "50-100 USD", "100-250 USD", "250-500 USD", "500-1000 USD", "1000-1500 USD", "1500-2000 USD"],
            ["All",39, 105, 150, 137, 157, 90, 29]
          ],
          type: "radar",
          colors: {
            All: "#0a7975",
          },
          labels: true
        },
        legend :{
            show:false
        },
        radar: {
          axis: {
            max: 200
          },
          level: {
            depth: 5,
            text: {
                show: false
            }
          },
          direction: {
            clockwise: true
          }
        },
        bindto: "#chart-job-listings-revenue"
      });

      // add ability to select which technology is being visualised
      const selector = document.getElementById('revenue-selector')
      selector.addEventListener('change', selectorChangeEvent)

      // listen for selector to change and react accordingly
      function selectorChangeEvent() {
        switch (selector.value) {
            case 'All':
                chart.load({
                    columns: [
                        ["All",39, 105, 150, 137, 157, 90, 29]
                    ],
                    unload:["JavaScript", "C++", "ML", "Ruby", "PHP"]
                });
                chart.internal.maxValue = 200
            break;
            case 'JavaScript':
                chart.load({
                    columns: [
                        ["JavaScript",124, 376, 285, 73, 69, 20, 13]
                    ],
                    unload:["All", "C++", "ML", "Ruby", "PHP"]
                });
                chart.internal.maxValue = 400
            break;
            case 'C++':
                chart.load({
                    columns: [
                        ["C++",34, 58, 117, 281, 361, 131, 40]
                    ],
                    unload:["All", "JavaScript", "ML", "Ruby", "PHP"]
                });
                chart.internal.maxValue = 400
            break;
            case 'ML':
                chart.load({
                    columns: [
                        ["ML",0, 15, 87, 198, 211, 144, 50]
                    ],
                    unload:["All", "JavaScript", "C++", "Ruby", "PHP"]
                });
                chart.internal.maxValue = 300
            break;
            case 'Ruby':
                chart.load({
                    columns: [
                        ["Ruby",34, 72, 77, 70, 63, 42, 21]
                    ],
                    unload:["All", "JavaScript", "C++", "ML", "PHP"]
                });
                chart.internal.maxValue = 200
            break;
            case 'PHP':
                chart.load({
                    columns: [
                        ["PHP",139, 126, 186, 63, 79, 111, 18]
                    ],
                    unload:["All", "JavaScript", "C++", "ML", "Ruby"]
                });
                chart.internal.maxValue = 200
            break;

        }
      }

}

// hacky await fix to adjust the legend's Y position after it's been created
async function modifyLegendPos() {
    await drawPopularTechBubble()
    var legend = document.getElementById("chart-job-listings-popular-tech").childNodes[0].childNodes[2]
    console.log(legend)
    legend.style.transform = "translateY(440px)"
}


// call functions
drawTimeOfYearChart()
drawTimeOfDayChart()
drawPopularTechBubble()
modifyLegendPos()
drawRadar()
