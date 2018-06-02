
 
            chart: function(symbol, time){
            },
            setUpCharts: function(response){
                       
            }
        },
        computed: {
            stocks: function(){
                var stocks = this.stockies;
                if(this.stock_symbol){
                    stocks =  stocks.filter(stock=>stock.symbol == this.stock_symbol)
                }
                if(this.sort){
                    stocks = _.orderBy(stocks,this.sort,this.sortBy ? 'asc' : 'desc')
                }
                return stocks;
            }
        },
        data:{
            stockies: [],
            sort:'sn',
            sortBy: true,
            stock_symbol:'',
        }
    })
}
