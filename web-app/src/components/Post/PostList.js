import React, {Component} from 'react';
import PropTypes from 'prop-types';
import TableHead from "@material-ui/core/TableHead";
import Table from "@material-ui/core/Table";
import Paper from "@material-ui/core/Paper";
import TableRow from "@material-ui/core/TableRow";
import TableCell from "@material-ui/core/TableCell";
import TableBody from "@material-ui/core/TableBody";
import TablePagination from "@material-ui/core/TablePagination";
import withStyles from "@material-ui/core/es/styles/withStyles";
import {lighten} from '@material-ui/core/styles/colorManipulator';
import Checkbox from "@material-ui/core/Checkbox";
import Tooltip from "@material-ui/core/Tooltip";
import TableSortLabel from "@material-ui/core/TableSortLabel";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";
import IconButton from "@material-ui/core/IconButton";
import DeleteIcon from '@material-ui/icons/Delete';
import FilterListIcon from '@material-ui/icons/FilterList';
import classNames from 'classnames';
import axios from "../../axios/axios";
import CircularProgress from "@material-ui/core/CircularProgress";
import {connect} from "react-redux";
import {toast} from "react-toastify";
import Grid from "@material-ui/core/Grid";
import Button from "@material-ui/core/Button";
import Redirect from "react-router-dom/es/Redirect";
import {withRouter} from "react-router-dom";
import SearchBox from "../SearchBox/SearchBox";

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

let counter = 0;

function createData(name, calories, fat, carbs, protein) {
    counter += 1;
    return {id: counter, name, calories, fat, carbs, protein};
}

function desc(a, b, orderBy) {
    if (b[orderBy] < a[orderBy]) {
        return -1;
    }
    if (b[orderBy] > a[orderBy]) {
        return 1;
    }
    return 0;
}

function stableSort(array, cmp) {
    const stabilizedThis = array.map((el, index) => [el, index]);
    stabilizedThis.sort((a, b) => {
        const order = cmp(a[0], b[0]);
        if (order !== 0) return order;
        return a[1] - b[1];
    });
    return stabilizedThis.map(el => el[0]);
}

function getSorting(order, orderBy) {
    return order === 'desc' ? (a, b) => desc(a, b, orderBy) : (a, b) => -desc(a, b, orderBy);
}

const rows = [
    {id: 'id', numeric: false, disablePadding: true, label: 'Id Kategori'},
    {id: 'tittle', numeric: false, disablePadding: true, label: 'Tytuł Postu'},
    {id: 'descryption', numeric: false, disablePadding: true, label: 'Krótki opis'},
];

class EnhancedTableHead extends React.Component {
    createSortHandler = property => event => {
        this.props.onRequestSort(event, property);
    };

    render() {
        const {onSelectAllClick, order, orderBy, numSelected, rowCount, user} = this.props;

        return (
            <TableHead>
                <TableRow>
                    {rows.map(
                        row => (
                            <TableCell
                                key={row.id}
                                align={row.numeric ? 'right' : 'left'}
                                padding={'default'}
                                sortDirection={orderBy === row.id ? order : false}
                            >
                                <Tooltip
                                    title="Sort"
                                    placement={row.numeric ? 'bottom-end' : 'bottom-start'}
                                    enterDelay={300}
                                >
                                    <TableSortLabel
                                        active={orderBy === row.id}
                                        direction={order}
                                        onClick={this.createSortHandler(row.id)}
                                    >
                                        {row.label}
                                    </TableSortLabel>
                                </Tooltip>
                            </TableCell>
                        ),
                        this,
                    )}
                    {this.props.user.length !== 0 &&
                    <React.Fragment>
                        {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                        <TableCell padding="default">
                        </TableCell>
                        }
                    </React.Fragment>
                    }
                </TableRow>
            </TableHead>
        );
    }
}

EnhancedTableHead.propTypes = {
    numSelected: PropTypes.number.isRequired,
    onRequestSort: PropTypes.func.isRequired,
    onSelectAllClick: PropTypes.func.isRequired,
    order: PropTypes.string.isRequired,
    orderBy: PropTypes.string.isRequired,
    rowCount: PropTypes.number.isRequired,
};

EnhancedTableHead = connect(mapStateToProps)(EnhancedTableHead);

const toolbarStyles = theme => ({
    root: {
        paddingRight: theme.spacing.unit,
    },
    highlight:
        theme.palette.type === 'light'
            ? {
                color: theme.palette.secondary.main,
                backgroundColor: lighten(theme.palette.secondary.light, 0.85),
            }
            : {
                color: theme.palette.text.primary,
                backgroundColor: theme.palette.secondary.dark,
            },
    spacer: {
        flex: '1 1 100%',
    },
    actions: {
        color: theme.palette.text.secondary,
    },
    title: {
        flex: '0 0 auto',
    },
});

let EnhancedTableToolbar = props => {
    const {numSelected, classes, query, handleChangeQuery, getAll} = props;

    return (
        <React.Fragment>
            <Toolbar
                className={classNames(classes.root, {
                    [classes.highlight]: numSelected > 0,
                })}
            >
                <div className={classes.title}>
                    <Typography variant="h6" id="tableTitle">
                        Posty
                    </Typography>
                </div>
                <div className={classes.spacer}/>
                <div className={classes.actions}>
                    <Tooltip title="Filter list">
                        <IconButton aria-label="Filter list">
                            <FilterListIcon/>
                        </IconButton>
                    </Tooltip>
                </div>
            </Toolbar>
            <SearchBox query={query} handleChangeQuery={handleChangeQuery} getAll={getAll}/>
        </React.Fragment>
    );
};

EnhancedTableToolbar.propTypes = {
    classes: PropTypes.object.isRequired,
    numSelected: PropTypes.number.isRequired,
};

EnhancedTableToolbar = withStyles(toolbarStyles)(EnhancedTableToolbar);

const styles = theme => ({
    root: {
        width: '100%',
        marginTop: theme.spacing.unit * 3,
        margin: 'auto',
        overflow: "auto",
    },
    table: {
        minWidth: 1020,
        overflow: "auto",
    },
    tableWrapper: {
        overflowX: 'auto',
        overflow: "auto",
    },
    paper: {
        margin: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
        overflow: "auto",
    },
    loginPaper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
});


class PostList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            order: 'asc',
            orderBy: 'calories',
            selected: null,
            data: {},
            page: 0,
            rowsPerPage: 5,
            loading: true,
            count: 0,
            query: '',
        };
    }

    componentDidMount = () => {
        this.getAllCategory();
    };


    getAllCategory = () => {
        this.setState({
            loading: true,
        });
        axios.get(`/api/v1/posts?page=${this.state.page + 1}&limit=${this.state.rowsPerPage}&query=${this.state.query}`)
            .then((response) => {
                this.setState({
                    data: response.data.data,
                    loading: false,
                    count: response.data.total
                });
                console.log(response.data.data);
            }).catch((e) => {
            this.setState({
                data: [],
                loading: false,
            });
        })
    };

    handleRequestSort = (event, property) => {
        const orderBy = property;
        let order = 'desc';

        if (this.state.orderBy === property && this.state.order === 'desc') {
            order = 'asc';
        }

        this.setState({order, orderBy});
    };

    handleChangeQuery = (e) => {
        const query = e.target.value;
        this.setState({query});
    };

    handleSelectAllClick = event => {
        if (event.target.checked) {
            this.setState(state => ({selected: state.data.map(n => n.id)}));
            return;
        }
        this.setState({selected: []});
    };

    handleChangePage = (event, page) => {
        console.log(page);
        this.setState({page}, () => {
            this.getAllCategory();
        });
    };

    handleChangeRowsPerPage = event => {
        this.setState({rowsPerPage: event.target.value}, () => {
            this.getAllCategory();
        });
    };

    isSelected = id => this.state.selected !== null;

    render() {
        const {classes} = this.props;
        const {data, order, orderBy, rowsPerPage, page, count} = this.state;
        const emptyRows = rowsPerPage - Math.min(rowsPerPage, data.length - page * rowsPerPage);
        if (this.state.loading === true) {
            return (
                <Paper className={classes.paper}>
                    <CircularProgress color="secondary"/>
                </Paper>
            );
        }

        return (
            <Grid container className={classes.root} spacing={24}>
                <Grid item xs={12} md={12}>
                    <Paper>
                        <EnhancedTableToolbar
                                              query={this.state.query} handleChangeQuery={this.handleChangeQuery}
                                              getAll={this.getAllCategory}
                        />
                        <div className={classes.tableWrapper}>
                            <Table className={classes.table} aria-labelledby="tableTitle">
                                <EnhancedTableHead
                                    order={order}
                                    orderBy={orderBy}
                                    onRequestSort={this.handleRequestSort}
                                    rowCount={data.length}
                                />
                                <TableBody>
                                    {this.state.data
                                        .map(n => {
                                            return (
                                                <TableRow
                                                    hover
                                                    role="checkbox"
                                                    tabIndex={-1}
                                                    key={n.id}
                                                    onClick={() => {
                                                        console.log('dzial;a');
                                                        this.props.history.push("/post/" + n.slug)
                                                    }}
                                                >
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {n.id}
                                                    </TableCell>
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {n.title}
                                                    </TableCell>
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {n.shortDescription}
                                                    </TableCell>
                                                </TableRow>
                                            );
                                        })}
                                    {emptyRows > 0 && (
                                        <TableRow>
                                            <TableCell colSpan={6}/>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                        <TablePagination
                            rowsPerPageOptions={[5, 10, 25]}
                            component="div"
                            count={count}
                            rowsPerPage={rowsPerPage}
                            page={page}
                            backIconButtonProps={{
                                'aria-label': 'Poprzednia strona',
                            }}
                            nextIconButtonProps={{
                                'aria-label': 'Nastepna strona',
                            }}
                            onChangePage={this.handleChangePage}
                            onChangeRowsPerPage={this.handleChangeRowsPerPage}
                        />
                    </Paper>
                </Grid>
            </Grid>
        );
    }
}

PostList.propTypes = {};

export default withRouter(connect(mapStateToProps)(withStyles(styles)(PostList)));